<?php

namespace qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface DbSchema
{

  /**
   * @return DbDictionary
   */
  function getDbDictionary();

  // DDL
  function createTable( Table $Table, $IfNotExists = false, $DropIfExists = false );

  function dropTable( $TableName );
}
