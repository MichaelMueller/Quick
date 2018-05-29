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

  /**
   * will call createTable(..., true, false) for all tables of the schema
   */
  function install( Schema $Schema, $DropTables = false );

  /**
   * 
   * @param \qck\Sql\Interfaces\Table $Table
   * @param bool $IfNotExists
   * @param bool $DropIfExists
   */
  function createTable( Table $Table, $IfNotExists = true, $DropIfExists = false );

  /**
   * 
   * @param string $TableName
   */
  function dropTable( $TableName );
}
