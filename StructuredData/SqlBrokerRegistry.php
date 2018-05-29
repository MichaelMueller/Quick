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

  /**
   *
   * @var array
   */
  protected $Brokers;

}
