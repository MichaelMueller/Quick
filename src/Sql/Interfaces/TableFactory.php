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
   * @return StandardTable
   */
  function createStandardTable( $Name, Column $PrimaryKeyColumn, array $Columns,
                                $Hidden = false );

  /**
   * create a relation table using the connected standard tables
   * @param \Qck\Sql\Interfaces\StandardTable $Left
   * @param \Qck\Sql\Interfaces\StandardTable $Right
   * @return RelationTable
   */
  function createRelationTable( StandardTable $Left, StandardTable $Right,
                                array $Columns = [] );
}
