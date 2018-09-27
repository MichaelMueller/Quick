<?php

namespace Qck\Sql\Interfaces;

use \Qck\Expression\Interfaces\BooleanExpression;

/**
 * Represents a Column
 * 
 * @author muellerm
 */
interface QueryFactory
{

  /**
   * 
   * @param string $TableName
   * @param array $Data
   * @return Query
   */
  function createInsert( $TableName, array $Data );

  /**
   * 
   * @param string $TableName
   * @param array $Data
   * @return Query
   */
  function createUpdate( $TableName, array $Data, BooleanExpression $BooleanExpression );

  /**
   * 
   * @param string $Name
   * @return Query
   */
  function createDelete( $TableName, BooleanExpression $BooleanExpression );

  /**
   * 
   * @param string $Name
   * @return Select
   */
  function createSelect( $TableName, BooleanExpression $BooleanExpression );
}
