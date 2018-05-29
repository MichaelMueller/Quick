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
   * @param \qck\StructuredData\Interfaces\NodeSelect $NodeSelect
   * @return array an array of UnloadedNodes
   */
  function select( NodeSelect $NodeSelect );

  /**
   * 
   * @param type $Fqcn
   * @param \qck\StructuredData\Interfaces\Expression $Expression
   */
  function delete( $Fqcn, Expression $Expression );

  /**
   * 
   */
  function commit();
}
