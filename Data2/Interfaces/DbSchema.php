<?php

namespace qck\Data2\Interfaces;

/**
 *
 * @author muellerm
 */
interface DbSchema
{

  /**
   * @return ObjectSchema
   */
  function getObjectSchema( $Fqcn );

  /**
   * 
   * @param \qck\Sql\Interfaces\DbSchema $DbSchema
   */
  function applyTo( \qck\Sql\Interfaces\DbSchema $DbSchema );
}
