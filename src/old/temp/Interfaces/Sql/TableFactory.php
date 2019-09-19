<?php

namespace Qck\Interfaces\Sql;

/**
 *
 * @author muellerm
 */
interface TableFactory
{

  /**
   * create a standard sql table
   * @param string $Name
   * @param \Qck\Interfaces\Sql\Column $PrimaryKeyColumn
   * @return StandardTable
   */
  function createStandardTable( $Name, Column $PrimaryKeyColumn, array $Columns=[],
                                $Hidden = false );

  /**
   * create a relation table using the connected standard tables
   * @param \Qck\Interfaces\Sql\StandardTable $Left
   * @param \Qck\Interfaces\Sql\StandardTable $Right
   * @return RelationTable
   */
  function createRelationTable( StandardTable $Left, StandardTable $Right,
                                array $Columns = [] );
}
