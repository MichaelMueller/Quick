<?php

namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface SqlDb
{

  /**
   * @return SqlDialect
   */
  function getSqlDialect();

  function beginTransaction();

  // DML
  function insert( $TableName, array $ColumnNames, array $Values );

  function update( $TableName, array $ColumnNames, array $Values, Expression $Expression );

  function delete( $TableName, Expression $Expression );

  function select( SqlSelect $SqlSelect );

  // DDL
  function createTable( $TableName, array $Columns, $ifNotExists = true );

  function renameTable( $TableName, $NewTableName );

  function addColumn( $TableName, SqlColumn $Column );

  function modifyColumn( $TableName, SqlColumn $OldColumn, SqlColumn $NewColumn );

  function dropColumn( $TableName, $ColumnName );

  function dropTable( $TableName );

  function commit();
}
