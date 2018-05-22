<?php

namespace qck\db\Sql;

/**
 *
 * @author muellerm
 */
interface Database extends TransactionalDatabase
{

  // DML
  function insert( $TableName, array $ColumnNames, array $Values );

  function update( $TableName, array $ColumnNames, array $Values, Expression $Expression );

  function delete( $TableName, Expression $Expression );

  function select( SqlSelect $SqlSelect );
}
