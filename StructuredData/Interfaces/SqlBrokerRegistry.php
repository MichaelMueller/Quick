<?php

namespace qck\StructuredData\Interfaces;

/**
 *
 * @author muellerm
 */
interface SqlBrokerRegistry
{

  /**
   * @return SqlBroker
   */
  function getBroker( $Fqcn );

  /**
   * @return \qck\Sql\Interfaces\Schema
   */
  function addToSchema( \qck\Sql\Interfaces\DbSchema $DbSchema );
}
