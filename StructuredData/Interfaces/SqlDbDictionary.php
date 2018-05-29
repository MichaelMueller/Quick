<?php

namespace qck\StructuredData\Interfaces;

/**
 *
 * @author muellerm
 */
interface SqlDbDictionary
{
  /**
   * @return string
   */
  function getIntDatatype();

  /**
   * @return string
   */
  function getStringDatatype( $MinLength = 0, $MaxLength = 255 );
}
