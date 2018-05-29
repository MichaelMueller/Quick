<?php

namespace qck\StructuredData;

use qck\Expressions\Abstracts\Expression as x;

/**
 *
 * @author muellerm
 */
class SqlNodeDb extends \qck\Data\Abstracts\NodeDb implements Interfaces\NodeDb
{

  function __construct( \qck\Sql\Interfaces\Db $Db,
                        Interfaces\SqlBrokerRegistry $BrokerRegistry )
  {
    $this->Db = $Db;
    $this->BrokerRegistry = $BrokerRegistry;
  }

  public function commit()
  {
    $BrokerRegistry = $this->BrokerRegistry;
    $Db = $this->Db;

    /* @var $Node \qck\Data\Interfaces\PersistableNode */
    foreach ( $this->Nodes as $Node )
      if ( $Node->getId() === null )
        $BrokerRegistry->getBroker( $Node->getFqcn() )->insert( $Db, $Node );

    foreach ( $this->Nodes as $Node )
      if ( $BrokerRegistry->getBroker( $Node->getFqcn() )->getVersion( $this->Db, $Node->getId() ) < $Node->getVersion() )
        $BrokerRegistry->getBroker( $Node->getFqcn() )->update( $Db, $Node );
  }

  public function delete( $Fqcn, $Id )
  {
    $this->BrokerRegistry->getBroker( $Fqcn )->delete( $this->Db, $Id );
    $this->forgetNode( $Fqcn, $Id );
  }

  public function deleteWhere( $Fqcn, Interfaces\Expression $Expression )
  {
    $Ids = $this->BrokerRegistry->getBroker( $Fqcn )->select( $this->Db, $Expression );
    $i = 0;
    for ( $i = 0; $i < count( $Ids ); $i++ )
      $this->delete( $Fqcn, $Ids[ $i ] );
    return $i + 1;
  }

  public function load( $Fqcn, $Id )
  {
    /* @var $Node \qck\Data\Interfaces\PersistableNode */
    $Node = $this->BrokerRegistry->getBroker( $Fqcn )->load( $this->Db, $Id, $this );
    if ( $Node )
    {
      /* @var $ExistingNode \qck\Data\Interfaces\PersistableNode */
      $ExistingNode = $this->findNode( $Fqcn, $Id );
      if ( $ExistingNode )
      {
        $ExistingNode->setData( $Node->getData() );
        $ExistingNode->setVersion( $Node->getVersion() );
        $Node = $ExistingNode;
      }
      return $Node;
    }
    return null;
  }

  public function select( $Fqcn, \qck\Expressions\Interfaces\Expression $Expression,
                          $Offset = null, $Limit = null, $OrderCol = null,
                          $Descending = true )
  {
    $Ids = $this->BrokerRegistry->getBroker( $Fqcn )->select( $Db, $Expression, $Offset, $Limit, $OrderCol, $Descending );
    $Nodes = [];
    foreach ( $Ids as $Id )
      $Nodes[] = $this->load( $Fqcn, $Id );

    return $Nodes;
  }

  /**
   *
   * @var \qck\Sql\Interfaces\Db
   */
  protected $Db;

  /**
   *
   * @var Interfaces\SqlBrokerRegistry
   */
  protected $BrokerRegistry;

}
