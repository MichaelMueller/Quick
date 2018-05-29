<?php

namespace qck\GraphStorage\Sql;

/**
 * Description of NodeSchema
 *
 * @author muellerm
 */
class NodeSchema
{

  function applyTo( SchemaInterface $SchemaInterface )
  {
    $Table = new Table( $this->getTableName() );
    $Table->addColumn( new Column( "Id", $SchemaInterface->getDictionary()->getStringDatatype( 36, 36 ) ) );
    $Table->addColumn( new Column( "ModifiedTime", $SchemaInterface->getDictionary()->getIntDataType() ) );
    $Table->addColumn( new Column( "Data", $SchemaInterface->getDictionary()->getStringDatatype( 0, 16777215 ) ) );
    $Table->setPrimaryKeyColumn( "Id" );
    
    $DataTable = new Table( $this->getDataTableName() );
    $DataTable->addColumn( new Column( "Id", $SchemaInterface->getDictionary()->getStringDatatype( 36, 36 ) ) );
    $Table->addColumn( new Column( "Key", $SchemaInterface->getDictionary()->getStringDatatype( 0, 1024 ) ) );
    $Table->addColumn( new Column( "Value", $SchemaInterface->getDictionary()->getStringDatatype( 0, 65535 ) ) );
    $Table->setPrimaryKeyColumn("Id");
    
    $SchemaInterface->createTable( $Table );
  }

  function store( \qck\GraphStorage\PersistableNode $Node, Database $Db )
  {
  }

  function load( $Id, Database $Db )
  {
    $SqlSelect = new Select( $this->getTableName() );
    $SqlSelect->setExpression( new Expressions\IdExpression( $Id ) );
    $DataArray = $Db->select( $SqlSelect )->fetch( \PDO::FETCH_ASSOC );
    if ( $DataArray )
    {
      $ModTime = $DataArray[ "ModifiedTime" ];
      unset( $DataArray[ "Id" ] );
      unset( $DataArray[ "ModifiedTime" ] );
      $Node = new \qck\GraphStorage\Node( $Id );
      $Node->setModifiedTime( $ModTime );
      $Node->setData( $DataArray );
      return $Node;
    }
    return null;
  }

  protected function getTableName()
  {
    return str_replace( "\\", "_", \qck\GraphStorage\Node::class );
  }
  
  protected function getDataTableName()
  {
    return str_replace( "\\", "_", \qck\GraphStorage\Node::class )."_Data";
  }
}
