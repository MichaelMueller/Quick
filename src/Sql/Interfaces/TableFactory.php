<?php

namespace Qck\Sql\Interfaces;

/**
 *
 * @author muellerm
 */
interface TableFactory
{

  /**
   * create a standard sql table
   * @param string $Name
   * @param \Qck\Sql\Interfaces\Column $PrimaryKeyColumn
   */
  function createStandardTable( $Name, Column $PrimaryKeyColumn );

  /**
   * create a relation table
   * @param \Qck\Sql\Interfaces\ForeignKeyColumn $Left
   * @param \Qck\Sql\Interfaces\ForeignKeyColumn $Right
   */
  function createRelationTable( ForeignKeyColumn $Left, ForeignKeyColumn $Right );
}
