<?php

namespace qck\db\interfaces;

/**
 *
 * @author muellerm
 */
interface SchemaDiff
{

  /**
   * @return array
   */
  function getDroppedTables();

  /**
   * @return array
   */
  function getRenamedTables();


  /**
   * @return array
   */
  function getAddedTables();
}
