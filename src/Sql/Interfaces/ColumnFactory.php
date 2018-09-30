<?php

namespace Qck\Sql\Interfaces;

/**
 * Represents a Column
 * 
 * @author muellerm
 */
interface ColumnFactory
{

  const TINYTEXT = 255;
  const SHORTTEXT = 2048;
  const TEXT = 65535;
  const MEDIUMTEXT = 16777215;
  const LONGTEXT = 4294967295;

  /**
   * 
   * @param string $Name
   * @return Column
   */
  function createBoolColumn( $Name );

  /**
   * 
   * @param string $Name
   * @return Column
   */
  function createFloatColumn( $Name );

  /**
   * 
   * @param string $Name
   * @return Column
   */
  function createIntColumn( $Name );

  /**
   * 
   * @param string $Name
   * @param int $MinLength
   * @param int $MaxLength
   * @param bool $Blob
   * @return Column
   */
  function createStringColumn( $Name, $MinLength = 0, $MaxLength = self::SHORTTEXT,
                               $Blob = false );

  /**
   * 
   * @param \Qck\Sql\Interfaces\Interfaces\StandardTable $RefTable
   * @return Column
   */
  function createForeignKeyColumn( StandardTable $RefTable,
                                   $OnDelete = ForeignKeyColumn::SET_NULL,
                                   $OnUpdate = ForeignKeyColumn::CASCADE );
}
