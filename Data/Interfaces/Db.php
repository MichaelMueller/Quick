<?php

namespace qck\Data\Interfaces;

/**
 *
 * @author muellerm
 */
interface Db
{
  /**
   * 
   * @param string $Fqcn
   * @param int $Uuid
   */
  function load( $Fqcn, $Uuid );

  /**
   * 
   * @param type $Fqcn
   * @param \qck\StructuredData\Interfaces\Expression $Expression
   */
  function deleteOnCommit( $Fqcn, $Uuid );

  /**
   * 
   * @param \qck\StructuredData\Interfaces\Select $ObjectSelect
   * @return \qck\Data\Object array containing Objects
   */
  function select( $Fqcn, \qck\Expressions\Interfaces\Expression $Expression,
                   $Limit = null, $Offset = null, $OrderPropName = null,
                   $Descending = true );

  /**
   * 
   * @param type $Fqcn
   * @param \qck\StructuredData\Interfaces\Expression $Expression
   */
  function deleteOnCommitWhere( $Fqcn, Expression $Expression );

  /**
   * register objects recursively to be inserted or updated next time tto a commit() call
   * @param Object $Object
   */
  function register( Object $Object );

  /**
   * insert and update objects here
   */
  function commit();
}
