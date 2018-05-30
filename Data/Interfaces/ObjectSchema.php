<?php

namespace qck\Data\Interfaces;

/**
 *
 * @author muellerm
 */
interface ObjectSchema
{

  /**
   * @return string
   */
  function getFqcn();

  /**
   * 
   * @return array
   */
  function getPropertyNames( $WithIdProperty = false );

  /**
   * @param array $Data the orignal Data obtained from an object using getData()
   * @return array
   */
  function prepare( array $Data, $WithVersionProperty = true, $WithIdProperty = false );

  /**
   * @param array $Data the orignal Data obtained from an object using getData()
   * @return array
   */
  function recover( array $Data, ObjectDb $ObjectDb );

  /**
   * @return string
   */
  function getIdPropertyName();

  /**
   * @return Property
   */
  function getVersionPropertyName();

  /**
   * @return string
   */
  function getSqlTableName();

  /**
   * 
   * @param \qck\Sql\Interfaces\DbSchema $ObjectDbSchema
   */
  function applyTo( \qck\Sql\Interfaces\DbSchema $ObjectDbSchema );
}
