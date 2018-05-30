<?php

namespace qck\StructuredData;

/**
 *
 * @author muellerm
 */
class SqlBrokerRegistry implements Interfaces\SqlBrokerRegistry
{

  function __construct( array $Brokers = [] )
  {
    $this->Brokers = $Brokers;
  }

  public function addBroker( $Fqcn, Interfaces\SqlBroker $Broker )
  {
    $this->Brokers[ $Fqcn ] = $Broker;
  }

  public function getBroker( $Fqcn )
  {
    return $this->Brokers[ $Fqcn ];
  }

  public function addToSchema( \qck\Sql\Interfaces\DbSchema $DbSchema )
  {
    /* @var $Broker Interfaces\SqlBroker */
    foreach ( $this->Brokers as $Broker )
      $Broker->addToSchema( $DbSchema );
  }

  /**
   *
   * @var array
   */
  protected $Brokers;

}
