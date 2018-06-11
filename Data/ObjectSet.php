<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class ObjectSet extends Object
{

  const OBJECTS_FQCN_KEY = "ObjectsFqcn";

  function __construct( $ObjectsFqcn = Object::class )
  {
    $this->Data[ self::OBJECTS_FQCN_KEY ] = $ObjectsFqcn;
  }

  function getObjectsFqcn()
  {
    return $this->get( self::OBJECTS_FQCN_KEY );
  }

  function add( Object $Object )
  {
    if ( $this->indexOf( $Object ) === false )
    {
      $this->Objects[] = $Object;
      $this->setModified();
    }
  }

  function at( $Index )
  {
    return $this->Objects[ $Index ];
  }

  function size()
  {
    return count( $this->Objects );
  }

  function indexOf( Object $Object )
  {
    return array_search( $Object, $this->Objects );
  }

  function remove( Object $Object )
  {
    $Index = $this->indexOf( $Object );
    if ( $Index !== false )
    {
      unset( $this->Objects[ $Index ] );
      $this->setModified();
    }
  }

  protected $Objects = [];

}
