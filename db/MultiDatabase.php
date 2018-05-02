<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class MultiDatabase implements interfaces\NodeDb
{

  function __construct( interfaces\NodeDb $PrimaryNodeDb )
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

  public function unloadNode( interfaces\Node $Node )
  {
    foreach ( $this->NodeDbs as $NodeDb )
      $NodeDb->unloadNode($Node);
    
  }

  protected $NodeDbs = [];

}
