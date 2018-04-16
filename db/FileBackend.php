<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class FileBackend implements interfaces\Backend
{

  function __construct( $DataDir )
  {
    $this->DataDir = $DataDir;
  }

  function register( Node $Node )
  {
    $this->Nodes[] = $Node;
  }

  function commit()
  {
    /* @var $Node qck\db\Node */
    foreach ( $this->Nodes as $Node )
    {
      // CONVERT THE NODE TO A PLAIN ARRAY AND SAVE IT VIA JSON FILE
      $PodArray = $this->toPodArray( $Node );
      $Path = $this->DataDir . DIRECTORY_SEPARATOR . $this->getId( $Node );
      file_put_contents( $Path, json_encode( $PodArray, JSON_PRETTY_PRINT ) );
    }
  }

  function getId( Node $Node, $create = true )
  {
    // GET OR CREATE AN ID
    $index = array_search( $Node, $this->Nodes, true );

    if ( !isset( $this->Ids[ $index ] ) )
    {
      if ( !$create )
        return null;
      do
      {
        $Id = uniqid();
      }
      while ( file_exists( $this->DataDir . DIRECTORY_SEPARATOR . $Id ) );
      $this->Ids[ $index ] = $Id;
    }

    return $this->Ids[ $index ];
  }

  function toPodArray( Node $Node )
  {
    $PodArray = [];
    $Data = $Node->getData();

    foreach ( $Data as $key => $value )
    {
      // CONVERT OTHER NODE OBJECTS INTO A SUBARRAY WITH THE FQCN AND THE ID
      if ( $value instanceof Node )
        $PodArray[ $key ] = [ get_class( $value ), $this->getId( $Node ) ];
      else if ( is_object( $value ) )
        $PodArray[ $key ] = serialize( $value );
      else
        $PodArray[ $key ] = $value;
    }
  }

  public function loadData( interfaces\Node $Node )
  {
    $Id = $this->getId( $Node, false );
    if ( $Id )
    {
      $Path = $this->DataDir . DIRECTORY_SEPARATOR . $this->getId( $Node );
      if ( !file_exists( $Path ) )
        return null;

      $PodArray = json_decode( file_get_contents( $Path ), true );
      $Data = [];
      foreach ( $PodArray as $key => $value )
      {
        if ( is_array( $value ) && count( $value ) == 2 )
        {
          $value = new $value[ 0 ]( $this );
          $this->Ids[ count( $this->Nodes ) - 1 ] = $value[ 1 ];
        }
        else if ( is_array( $value ) && count( $value ) == 1 )
          $value = unserialize( $value[ 0 ] );
        $Data[ $key ] = $value;
      }
    }
    return null;
  }

  protected $DataDir;
  protected $Nodes = [];
  protected $Ids = [];

}
