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
   * @return A unique ID
   */
  function getUuid();

  /**
   * 
   * @return array
   */
  function getPropertyNames( $WithUuidProperty = false );

  /**
   * 
   * @param type $DataArray
   * @return array
   */
  function convertKeysToUuids( array $DataArray );

  /**
   * 
   * @param type $DataArray
   * @return array
   */
  function convertUuidsToKeys( $DataArray );

  /**
   * 
   * @param type $DataArray
   * @return array
   */
  function filterArray( $DataArray );

  /**
   * @param array $Data the orignal Data obtained from an object using getData()
   * @return array
   */
  function prepare( array $Data, $WithModifiedTimeProperty = true,
                    $WithUuidProperty = false );

  /**
   * @param array $Data the orignal Data obtained from an object using getData()
   * @return array
   */
  function recover( array $Data, ObjectDb $ObjectDb );

  /**
   * @return string
   */
  function getUuidPropertyName();

  /**
   * @return Property
   */
  function getModifiedTimePropertyName();

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
