<?php

namespace qck\Data\Interfaces;

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
   * @param string $Uuid
   * @return ObjectSchema
   */
  function getObjectSchemaByUuid( $Uuid );

  /**
   * @return string
   */
  function getFqcns();

  /**
   * 
   * @param \qck\Sql\Interfaces\DbSchema $ObjectDbSchema
   */
  function applyTo( \qck\Sql\Interfaces\DbSchema $ObjectDbSchema );
}
