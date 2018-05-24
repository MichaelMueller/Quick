<?php

namespace qck\db\Sql;

/**
 *
 * @author muellerm
 */
interface Database extends TransactionalDatabase
{

  /**
   * @return DatabaseSchemaInterface
   */
  function getDatabaseSchemaInterface();

  // DML
  function insert( $TableName, array $ColumnNames, array $Values );

  function update( $TableName, array $ColumnNames, array $Values, Expression $Expression );

  function delete( $TableName, Expression $Expression );

  function select( SqlSelect $SqlSelect );
}
