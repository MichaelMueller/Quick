<?php

namespace qck\StructuredData\Tests;

/**
 *
 * @author muellerm
 */
class StructuredDataTest extends \qck\core\abstracts\Test
{

  public function run( \qck\core\interfaces\AppConfig $config, array &$FilesToDelete = [] )
  {

    $SqliteFile = $this->getTempFile( $FilesToDelete );
    $SqliteDb = new \qck\Sql\SqliteDb( $SqliteFile );

    $BrokerRegistry = new \qck\StructuredData\SqlBrokerRegistry();
    $BrokerRegistry->addBroker( \qck\Data\Node::class, new \qck\StructuredData\NodeSqlBroker() );
    $BrokerRegistry->addToSchema( $SqliteDb );

    $SqlNodeDb = new \qck\StructuredData\SqlNodeDb( $SqliteDb, $BrokerRegistry );

    $UserNode = new \qck\Data\Node();
    $UserNode->Name = "Michael";
    $UserNode->Admin = true;

    $SqlNodeDb->register( $Node );
    $SqlNodeDb->commit();
  }

  public function getRequiredTests()
  {
    return [];
  }
}
