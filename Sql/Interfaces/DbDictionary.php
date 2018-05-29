<?php

namespace qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface DbDictionary
{

  /**
   * @return string
   */
  function getBoolDatatype();

  /**
   * @return string
   */
  function getIntDatatype();

  /**
   * @return string
   */
  function getPrimaryKeyAutoincrementAttribute();

  /**
   * @return string
   */
  function getStringDatatype( $MinLength = 0, $MaxLength = 255, $Blob = false );

  /**
   * @return string
   */
  function getFloatDatatype();

  /**
   * @return string
   */
  function getRegExpOperator();

  /**
   * @return string
   */
  function getStrlenFunctionName();
}
