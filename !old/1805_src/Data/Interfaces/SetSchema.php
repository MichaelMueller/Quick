<?php

namespace qck\Data\Interfaces;

/**
 *
 * @author muellerm
 */
interface SetSchema extends ObjectSchema
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
