<?php

namespace qck\Data;

use qck\Data\Interfaces\PersistableNode;
use qck\Data\Interfaces\IdProvider;

/**
 * holds and manages PersistableNode Objects in flat files
 * @author muellerm
 */
class FileNodeDb extends Abstracts\NodeDb
{

  function __construct( $DataDir )
  {
    $this->DataDir = $DataDir;
  }

  function commit()
  {
    /* @var $Node PersistableNode */
    foreach ( $this->Nodes as $Node )
    {
      $Fqcn = get_class( $Node );
      $this->createDirIfNeeded( $Fqcn );
      if ( $Node->getId() === null )
        $Node->setId( $this->createId( $Fqcn ) );
    }

    foreach ( $this->Nodes as $Node )
    {
      $Id = $Node->getId();
      $Fqcn = get_class( $Node );
      if ( $Node->getVersion() <= $this->getVersion( $Fqcn, $Id ) )
        continue;

      // go through the raw data to traverse the graph and collect the data
      $Data = $Node->getData();
      foreach ( $Data as $key => $value )
        if ( $value instanceof IdProvider )
          $Data[ $key ] = new NodeLazyLoader( $value->getFqcn(), $value->getId() );
      $SaveNode = clone $Node;
      $SaveNode->setData( $Data );

      $File = $this->getDataFile( $Fqcn, $Id );
      $VersionFile = $this->getVersionFile( $Fqcn, $Id );
      file_put_contents( $File, serialize( $SaveNode ), LOCK_EX );
      file_put_contents( $VersionFile, $Node->getVersion(), LOCK_EX );
    }
  }

  function load( $Fqcn, $Id )
  {
    $ExistingNode = $this->findNode( $Fqcn, $Id );
    if ( $ExistingNode && $ExistingNode->getVersion() <= $this->getVersion( $Id ) )
      return $ExistingNode;

    // Load the data
    $File = $this->getDataFile( $Fqcn, $Id );
    $LoadedNode = file_exists( $File ) ? unserialize( file_get_contents( $File ) ) : null;

    if ( $LoadedNode instanceof PersistableNode )
    {
      if ( $ExistingNode )
      {
        $ExistingNode->setData( $LoadedNode->getData() );
        $ExistingNode->setVersion( $LoadedNode->getVersion() );
        return $ExistingNode;
      }
      else
      {
        $this->Nodes[ $Id ] = $LoadedNode;
        foreach ( $LoadedNode->getData() as $Value )
          if ( $Value instanceof NodeLazyLoader )
            $Value->setNodeDb( $this );
        return $LoadedNode;
      }
    }
    return null;
  }

  public function delete( $Fqcn, $Id )
  {
    $File = $this->getDataFile( $Fqcn, $Id );
    $VerFile = $this->getVersionFile( $Fqcn, $Id );
    if ( file_exists( $File ) )
      unlink( $File );
    if ( file_exists( $VerFile ) )
      unlink( $VerFile );
    $this->forgetNode( $Fqcn, $Id );
  }

  protected function getVersion( $Fqcn, $Id )
  {
    $VersionFile = $this->getVersionFile( $Fqcn, $Id );
    return file_exists( $VersionFile ) ? file_get_contents( $VersionFile ) : -1;
  }

  protected function createId( $Fqcn )
  {
    $KeyFile = $this->getKeyFile( $Fqcn );
    $NextId = file_exists( $KeyFile ) ? file_get_contents( $KeyFile ) : 1;
    file_put_contents( $KeyFile, $NextId + 1, LOCK_EX );
    return $NextId;
  }

  protected function createDirIfNeeded( $Fqcn )
  {
    $File = $this->getDataDir( $Fqcn );
    if ( !is_dir( $File ) )
      mkdir( $File );
  }

  protected function getDataFile( $Fqcn, $Id )
  {
    return $this->getDataDir( $Fqcn ) . DIRECTORY_SEPARATOR . $Id . ".dat";
  }

  protected function getVersionFile( $Fqcn, $Id )
  {
    return $this->getDataDir( $Fqcn ) . DIRECTORY_SEPARATOR . $Id . ".version.dat";
  }

  protected function getKeyFile( $Fqcn )
  {
    return $this->getDataDir( $Fqcn ) . DIRECTORY_SEPARATOR . "keys.dat";
  }

  protected function getDataDir( $Fqcn )
  {
    return $this->DataDir . DIRECTORY_SEPARATOR . str_replace( "\\", "_", $Fqcn );
  }

  /**
   *
   * @var string
   */
  protected $DataDir;

}
