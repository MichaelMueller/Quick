<?php

namespace qck\db\abstracts;

/**
 * abstract implementation of a NodeDb. Extending classes will mostly deal
 * with the end-storage specific details (e.g. file based or sql or whatever)
 * 
 * @author muellerm
 */
abstract class NodeDb implements \qck\db\interfaces\NodeDb
{

  abstract protected function insertNode( \qck\db\interfaces\Node $Node );

  abstract protected function updateNode( \qck\db\ChangeLog $ChangeLog );

  /**
   * @return \qck\db\interfaces\Node or null
   */
  abstract protected function loadNode( $Uuid );

  public function add( \qck\db\interfaces\Node $Node )
  {
    $this->addRecursive( $Node );
  }

  public function commit()
  {
    /* @var $Node interfaces\Node */
    foreach ( $this->Nodes as $Node )
    {
      $Uuid = $Node->getUuid();
      /* @var $ChangeLog ChangeLog */
      $ChangeLog = isset( $this->ChangeLogs[ $Uuid ] ) ? $this->ChangeLogs[ $Uuid ] : null;

      // there is a change log -> node has already been written
      if ( $ChangeLog )
      {
        if ( $ChangeLog->nodeWasModified() == false )
          continue;
        $this->updateNode( $ChangeLog );
      }
      // write initially
      else
      {
        $this->insertNode( $Node );
        $this->ChangeLogs[ $Uuid ] = new \qck\db\ChangeLog( $Node );
      }
    }
  }

  public function getNode( $Uuid )
  {
    // check if node is available
    if ( isset( $this->Nodes[ $Uuid ] ) )
      return $this->Nodes[ $Uuid ];
    else
    {
      $Node = $this->loadNode( $Uuid );
      if ( $Node )
      {
        $this->add( $Node );
        $this->ChangeLogs[ $Uuid ] = new \qck\db\ChangeLog( $Node );
        return $Node;
      }
      else
        return null;
    }
  }

  function unloadNode( \qck\db\interfaces\Node $Node )
  {
    $Uuid = $Node->getUuid();
    if ( isset( $this->Nodes[ $Uuid ] ) )
      unset( $this->Nodes[ $Uuid ] );

    if ( isset( $this->ChangeLogs[ $Uuid ] ) )
      unset( $this->ChangeLogs[ $Uuid ] );
  }

  protected function addRecursive( \qck\db\interfaces\Node $Node )
  {
    if ( in_array( $Node, $this->Nodes, true ) )
      return;

    $this->Nodes[ $Node->getUuid() ] = $Node;
    foreach ( $Node->getData() as $value )
      if ( $value instanceof \qck\db\interfaces\Node )
        $this->addRecursive( $value );
  }

  /**
   * @var array 
   */
  protected $Nodes = [];

  /**
   * @var array 
   */
  protected $ChangeLogs = [];

}
