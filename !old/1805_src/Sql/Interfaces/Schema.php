<?php

namespace qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface Schema
{

  /**
   * @return array Of Table Objects
   */
  function getTables();

  /**
   * 
   * @param \qck\Sql\Interfaces\Interfaces\Table $Table
   */
  function addTable( Table $Table );
}
