<?php

namespace qck\StructuredData;

/**
 *
 * @author muellerm
 */
class NodeSqlBroker implements Interfaces\SqlBroker
{

  const TABLE_NAME = "Nodes";

  public function delete( \qck\Sql\Interfaces\Db $Db, $Id )
  {
    $Db->delete( self::TABLE_NAME, new \qck\Expressions\IdEquals( $Id ) );
  }

  public function getVersion( \qck\Sql\Interfaces\Db $Db, $Id )
  {
    $Select = new \qck\Sql\Select( self::TABLE_NAME, new \qck\Expressions\IdEquals( $Id ) );
    $Select->setColumns( "Version" );
    $Data = $Db->select( self::TABLE_NAME, new \qck\Expressions\IdEquals( $Id ) )->fetch( \PDO::FETCH_ASSOC );
    if ( $Data !== FALSE )
      return $Data[ "Version" ];
    return -1;
  }

  public function insert( \qck\Sql\Interfaces\Db $Db,
                          \qck\Data\Interfaces\PersistableNode $Node )
  {
    // go through the raw data to traverse the graph and collect the data
    $Data = $Node->getData();
    foreach ( $Data as $key => $value )
      if ( $value instanceof \qck\Data\Interfaces\IdProvider )
        $Data[ $key ] = new \qck\Data\NodeLazyLoader( $value->getFqcn(), $value->getId() );
    $Id = $Db->insert( self::TABLE_NAME, [ "Version", "Data" ], [ $Node->getVersion(), serialize( $Data ) ] );
    $Node->setId( $Id );
  }

  public function load( \qck\Sql\Interfaces\Db $Db, $Id,
                        \qck\Data\Interfaces\NodeDb $NodeDb )
  {
    $Data = $Db->select( self::TABLE_NAME, new \qck\Expressions\IdEquals( $Id ) )->fetch( \PDO::FETCH_ASSOC );
    if ( $Data === FALSE )
      return null;
    $Node = new \qck\Data\Node();
    $Node->setId( $Id );
    $Node->setVersion( $Data[ "Version" ] );
    $Data = $Data[ "Data" ];
    foreach ( $Data as $value )
      if ( $value instanceof \qck\Data\NodeLazyLoader )
        $value->setNodeDb( $NodeDb );
    $Node->setData( $Data );
  }

  public function select( \qck\Sql\Interfaces\Db $Db,
                          \qck\Expressions\Interfaces\Expression $Expression,
                          $Offset = null, $Limit = null, $OrderCol = null,
                          $Descending = true )
  {
    $Select = new \qck\Sql\Select( self::TABLE_NAME, $Expression );
    $Select->setColumns( "Id" );
    $Select->setOrderParams( $OrderCol, $Descending );
    $Select->setOffset( $Offset );
    $Select->setLimit( $Limit );
    return $Db->select( self::TABLE_NAME, new \qck\Expressions\IdEquals( $Id ) )->fetchAll( \PDO::FETCH_ASSOC );
  }

  public function update( \qck\Sql\Interfaces\Db $Db,
                          \qck\Data\Interfaces\PersistableNode $Node )
  {
    $Data = $Node->getData();
    foreach ( $Data as $key => $value )
      if ( $value instanceof \qck\Data\Interfaces\IdProvider )
        $Data[ $key ] = new \qck\Data\NodeLazyLoader( $value->getFqcn(), $value->getId() );
    $Id = $Db->update( self::TABLE_NAME, [ "Version", "Data" ], [ $Node->getVersion(), serialize( $Data ) ], new \qck\Expressions\IdEquals( $Id ) );
  }

  public function addToSchema( \qck\Sql\Interfaces\DbSchema $DbSchema )
  {
    $Table = new \qck\Sql\Table( self::TABLE_NAME );
    $Table->addColumn( new \qck\Sql\PrimaryKeyCol( "Id" ) );
    $Table->addColumn( new \qck\Sql\IntColumn( "Version" ) );
    $Table->addColumn( new \qck\Sql\StringColumn( "Data", 0, \qck\Sql\StringColumn::MEDIUMTEXT ) );
    $DbSchema->createTable( $Table );
  }
}
