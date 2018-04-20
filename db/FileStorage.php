<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class FileStorage implements interfaces\Storage, interfaces\Loader, interfaces\Observer, interfaces\Visitor
{

  function __construct( $DataDir )
  {
    $this->DataDir = $DataDir;
  }

  function handle( interfaces\Node $Node )
  {
    if ( $this->hasChanged( $Node ) == false )
      return;

    $PersistableArray = [];
    foreach ( $Node->getData() as $key => $value )
    {
      // CONVERT OTHER NODE OBJECTS INTO A SUBARRAY WITH THE FQCN AND THE ID
      if ( $value instanceof interfaces\Node )
        $value = [ get_class( $value ), $this->getOrCreateId( $value ) ];
      else if ( is_object( $value ) )
        $value = [ serialize( $value ) ];
      $PersistableArray[ $key ] = $value;
    }

    if($this->isRegistered($Node))
    {
      $Path = $this->getFilePath( $this->getId( $Node ) );
      $PersistableArray = array_merge($PersistableArray, $this->loadArray($Path));      
    }
    else
      $Path = $this->getFilePath( $this->register( $Node ) );
    
    file_put_contents( $Path, json_encode( $PersistableArray, JSON_PRETTY_PRINT ) );
    $this->setUnchanged( $Node );
  }

  function save( interfaces\Node $Node )
  {
    $Node->traverse( $this );
  }

  protected function getOrCreateId( interfaces\Node $Node )
  {
    return $this->isRegistered( $Node ) ? $this->getId( $Node ) : $this->register( $Node );
  }

  protected function getFilePath( $Id )
  {
    return $this->DataDir . DIRECTORY_SEPARATOR . $Id . ".json";
  }

  protected function register( interfaces\Node $Node, $Id = null )
  {
    $key = spl_object_hash( $Node );
    $fqcn = str_replace("\\", "_", get_class($Node));
    $i = 0;
    if ( !$Id )
    {
      do
      {
        $i++;
        $Id = $fqcn.".".strval($i);
      }
      while ( file_exists( $this->getFilePath( $Id ) ) );
    }
    $this->Nodes[ $key ] = [ "Id" => $Id, "Changed" => false ];
    $Node->addObserver( $this );
    return $Id;
  }

  protected function isRegistered( interfaces\Node $Node )
  {
    return isset( $this->Nodes[ spl_object_hash( $Node ) ] );
  }

  protected function getId( interfaces\Node $Node )
  {
    return $this->Nodes[ spl_object_hash( $Node ) ][ "Id" ];
  }

  protected function loadArray( $Path )
  {
    return json_decode( file_get_contents( $Path ), true );
  }

  function loadData( interfaces\Node $Node )
  {
    if ( $this->isRegistered( $Node ) )
    {
      $Path = $this->getFilePath( $this->getId( $Node ) );
      $PersistetArray = $this->loadArray($Path);

      $Data = [];
      foreach ( $PersistetArray as $key => $value )
      {
        if ( is_array( $value ) && count( $value ) == 2 )
        {
          $Fqcn = $value[ 0 ];
          $value = new $Fqcn();
          $this->register( $value, $value[ 1 ] );
        }
        else if ( is_array( $value ) && count( $value ) == 1 )
          $value = unserialize( $value[ 0 ] );
        $Data[ $key ] = $value;
      }
      return $Data;
    }

    return null;
  }

  public function changed( interfaces\Node $Node, $key, $newVal, $oldVal )
  {
    $this->Nodes[ spl_object_hash( $Node ) ][ "Changed" ] = true;
  }

  protected function setUnchanged( interfaces\Node $Node )
  {
    $this->Nodes[ spl_object_hash( $Node ) ][ "Changed" ] = false;
  }

  protected function hasChanged( interfaces\Node $Node )
  {
    $key = spl_object_hash( $Node );
    return isset( $this->Nodes[ $key ] ) ? $this->Nodes[ $key ][ "Changed" ] : true;
  }

  protected $DataDir;
  protected $Nodes = [];

}
