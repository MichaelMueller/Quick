<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class GraphSchemaDiff
{

  function addDiff( Diff $Diff )
  {
    $this->Diffs[] = $Diff;
  }

  function applyTo( Sql\DatabaseSchemaInterface $DatabaseSchemaInterface )
  {

    $DatabaseSchemaInterface->beginTransaction();

    /* @var $Diff Diff */
    foreach ( $this->Diffs as $Diff )
      $Diff->applyTo( $DatabaseSchemaInterface );

    $DatabaseSchemaInterface->commit();
  }
  
  protected $Diffs = [];

}
