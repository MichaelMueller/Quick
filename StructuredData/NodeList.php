<?php

namespace qck\StructuredData;

/**
 *
 * @author muellerm
 */
class NodeList implements \qck\Data\Interfaces\PersistableNode
{

  function __construct( $ContainedFqcn )
  {
    $this->ContainedFqcn = $ContainedFqcn;
  }

  function getContainedFqcn()
  {
    return $this->ContainedFqcn;
  }

  function add( \qck\Data\Interfaces\PersistableNode $Node )
  {
    $this->Data[] = $Node;
    $this->Version++;
  }

  function get( $key )
  {
    return $this->Data[ $key ];
  }

  function remove( $key )
  {
    unset( $this->Data[ $key ] );
  }

  function keys()
  {
    return array_keys( $this->Data );
  }

  function getId()
  {
    return $this->Id;
  }

  function getVersion()
  {
    return $this->Version;
  }

  function getData()
  {
    return $this->Data;
  }

  function setId( $Id )
  {
    $this->Id = $Id;
  }

  function setVersion( $Version )
  {
    $this->Version = $Version;
  }

  function setData( $Data )
  {
    foreach ( $Data as $Value )
      $Data[] = $Value;
    $this->Version++;
  }

  public function getFqcn()
  {
    return get_class( $this );
  }

  protected $Id;
  protected $Version;
  protected $Data = [];
  protected $ContainedFqcn;

}
