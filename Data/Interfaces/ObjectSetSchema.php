<?php

namespace qck\Data\Interfaces;

/**
 *
 * @author muellerm
 */
interface ObjectSetSchema extends ObjectSchema
{

  /**
   * @return string
   */
  function getItemsSqlTableName();

  /**
   * @return string
   */
  function getObjectUuidPropertyName();
}
