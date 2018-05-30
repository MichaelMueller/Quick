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

    $SqliteFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . str_replace( "\\", "_", StructuredDataTest::class ) . ".sqlite";
    if ( file_exists( $SqliteFile ) )
      unlink( $SqliteFile );

    $SqliteDb = new \qck\Sql\SqliteDb( $SqliteFile );

    $BrokerRegistry = new \qck\StructuredData\SqlBrokerRegistry();
    $BrokerRegistry->addBroker( \qck\Data\Node::class, new \qck\StructuredData\NodeSqlBroker() );
    $BrokerRegistry->addToSchema( $SqliteDb );

    $SqlNodeDb = new \qck\StructuredData\SqlNodeDb( $SqliteDb, $BrokerRegistry );

    $UserNode = new \qck\Data\Node();
    $UserNode->Name = "Michael";
    $UserNode->Admin = true;

    $SqlNodeDb->register( $UserNode );
    $SqlNodeDb->commit();
    $SqliteDb->closeConnection();

    $SqlNodeDb2 = new \qck\StructuredData\SqlNodeDb( $SqliteDb, $BrokerRegistry );
    $LoadedNode = $SqlNodeDb2->load( \qck\Data\Node::class, $UserNode->getId() );
    $this->assert( $LoadedNode == $UserNode );
  }

  public function getRequiredTests()
  {
    return [];
  }
}
