<?php

namespace Qck\Interfaces\Sql;

use \Qck\Interfaces\Expressions\BooleanExpression;

/**
 * Represents a Column
 * 
 * @author muellerm
 */
interface QueryFactory
{


  /**
   * 
   * @param Table $Table
   * @param array $Data
   * @return Query
   */
  function createInsert( Table $Table, array $Data );

  /**
   * 
   * @param Table $Table
   * @param array $Data
   * @return Query
   */
  function createUpdate( Table $Table, array $Data, BooleanExpression $BooleanExpression );

  /**
   * 
   * @param Table $Table
   * @return Query
   */
  function createDelete( Table $Table, BooleanExpression $BooleanExpression );

  /**
   * 
   * @param Table $Table
   * @return Select
   */
  function createSelect( Table $Table, BooleanExpression $BooleanExpression = null, array $Columns=[] );

  /**
   * 
   * @param Table[] $Tables
   * @return Select
   */
  public function createMultiTableSelect( array $Tables,
                                          \Qck\Interfaces\Expressions\BooleanExpression $BooleanExpression = null );
}
