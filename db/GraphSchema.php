<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class GraphSchema
{

  function addObjectSchema( $Id, ObjectSchema $ObjectSchema )
  {
    $this->ObjectSchemas[ $Id ] = $ObjectSchema;
  }

  function getObjectSchemaIds()
  {
    return array_keys( $this->ObjectSchemas );
  }

  /**
   * 
   * @param type $Id
   * @return ObjectSchema
   */
  function getObjectSchema( $Id )
  {
    return isset( $this->ObjectSchemas[ $Id ] ) ? $this->ObjectSchemas[ $Id ] : null;
  }

  protected $ObjectSchemas = [];

}
