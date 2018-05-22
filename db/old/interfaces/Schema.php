<?php

namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface Schema
{

  /**
   * @param string ObjectSchemaId
   * @return ObjectSchema
   */
  function getTableDefinition( $TableName );
  
  function getDiff(Schema $PreviousSchema);
}
