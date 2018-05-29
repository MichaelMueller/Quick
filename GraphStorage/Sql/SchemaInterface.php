<?php

namespace qck\GraphStorage\Sql;

/**
 *
 * @author muellerm
 */
interface SchemaInterface
{
  /**
   * @return Dictionary
   */
  function getDictionary();

  // DDL
  function createTable( Table $Table );

  function renameTable( $TableName, $NewTableName );

  function dropTable( $TableName );
}
