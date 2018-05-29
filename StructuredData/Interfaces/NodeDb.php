<?php

namespace qck\StructuredData\Interfaces;

/**
 *
 * @author muellerm
 */
interface NodeDb extends \qck\Data\Interfaces\NodeDb
{

  /**
   * 
   * @param \qck\StructuredData\Interfaces\Select $NodeSelect
   * @return \qck\Data\Node Containing UnloadedNodes
   */
  function select( $Fqcn, \qck\Expressions\Interfaces\Expression $Expression,
                   $Offset = null, $Limit = null, $OrderCol = null, $Descending = true );

  /**
   * 
   * @param type $Fqcn
   * @param \qck\StructuredData\Interfaces\Expression $Expression
   */
  function deleteWhere( $Fqcn, Expression $Expression );

  /**
   * 
   */
  function commit();
}
