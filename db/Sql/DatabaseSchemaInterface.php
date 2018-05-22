<?php

namespace qck\db\Sql;

/**
 *
 * @author muellerm
 */
interface DatabaseSchemaInterface extends TransactionalDatabase
{

  /**
   * @return DatabaseDictionary
   */
  function getDatabaseDictionary();

  // DDL
  function createTable( Table $Table );

  function renameTable( $TableName, $NewTableName );
  
  function dropTable( $TableName );
}
