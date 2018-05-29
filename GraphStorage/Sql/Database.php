<?php

namespace qck\GraphStorage\Sql;

/**
 *
 * @author muellerm
 */
interface Database
{

  /**
   * @return DatabaseSchemaInterface
   */
  function getDatabaseSchemaInterface();

  // DML
  function insert( $TableName, array $ColumnNames, array $Values );

  function update( $TableName, array $ColumnNames, array $Values, Expression $Expression );

  function delete( $TableName, Expression $Expression );

  /**
   * 
   * @param \qck\GraphStorage\Sql\SqlSelect $SqlSelect
   * @return \PDOStatement PDOStatement
   */
  function select( SqlSelect $SqlSelect );

  // DDL
  function createTable( Table $Table );

  function renameTable( $TableName, $NewTableName );

  function dropTable( $TableName );

  // TRANSACTIONS
  function beginTransaction();

  function commit();

  // DICTIONARY INFO
  function getIntDatatype();
  
  function getStringDatatype( $MinLength = 0, $MaxLength = 255 );

  function getRegExpOperator();
  
  function getStrlenFunctionName();
}
