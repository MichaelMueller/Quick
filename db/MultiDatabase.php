<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class MultiDatabase implements interfaces\NodeDb
{

  function __construct( $PrimaryNodeDb )
  {
    $this->NodeDbs[] = $PrimaryNodeDb;
  }

  function addNodeDb( $NodeDb )
  {
    $this->NodeDbs[] = $NodeDb;
  }

  public function add( interfaces\Node $Node )
  {
    foreach ( $this->NodeDbs as $NodeDb )
      $NodeDb->add( $Node );
  }

  public function commit()
  {
    foreach ( $this->NodeDbs as $NodeDb )
      $NodeDb->commit();
  }

  public function getNode( $Uuid )
  {
    return $this->NodeDbs[ 0 ]->getNode( $Uuid );
  }

  protected $NodeDbs = [];

}
