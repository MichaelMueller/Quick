<?php

namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
class SqlSchema
{


  // DDL
  function createTable( $TableName, array $Columns, $ifNotExists = true );

  function renameTable( $TableName, $NewTableName );

  function addColumn( $TableName, SqlColumn $Column );

  function modifyColumn( $TableName, SqlColumn $OldColumn, SqlColumn $NewColumn );

  function dropColumn( $TableName, $ColumnName );

  function dropTable( $TableName );

  function commit();
}
