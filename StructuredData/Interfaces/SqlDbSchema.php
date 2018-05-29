<?php

namespace qck\StructuredData\Interfaces;

/**
 *
 * @author muellerm
 */
interface SqlDbSchema
{
  /**
   * @return SqlDbDictionary
   */
  function getSqlDbDictionary();

  // DDL
  function createTable( SqlTable $Table );

  function renameTable( $TableName, $NewTableName );

  function dropTable( $TableName );
}
