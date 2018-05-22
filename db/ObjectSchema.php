<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class ObjectSchema
{

  function addProperty( $Id, Property $Property )
  {
    $this->ObjectSchemas[ $Id ] = $Property;
  }

  function getPropertyIds()
  {
    return array_keys( $this->Properties );
  }

  /**
   * 
   * @param type $Id
   * @return Property
   */
  function getProperty( $Id )
  {
    return isset( $this->Properties[ $Id ] ) ? $this->Properties[ $Id ] : null;
  }

  function getSqlTableName()
  {
    return str_replace( "\\", ".", $this->Fqcn );
  }

  function getFqcn()
  {
    return $this->Fqcn;
  }

  protected $Fqcn;
  protected $Properties;

}
