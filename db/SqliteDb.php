<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class SqliteDb extends abstracts\NodeDb
{

  const STRING = 0;
  const UUID = 1;
  const SERIALIZED_OBJECT = 2;

  function __construct( $SqliteFile )
  {
    $this->SqliteFile = $SqliteFile;
  }

  protected function insertNode( interfaces\Node $Node )
  {
    // write metadata
    $MetaData = [ $Node->getUuid(), $Node->getModifiedTime(), get_class( $Node ) ];
    $stmt1 = $this->getPdo()->prepare( 'insert into Nodes (Uuid, ModifiedTime, Fqcn) values(?,?,?);' );
    $stmt1->execute( $MetaData );
    $Id = $this->getPdo()->lastInsertId();

    // write data
    $insertStatement = $this->getPdo()->prepare( 'insert into NodeData (NodeId, Key, Value, Type) values(?,?,?,?);' );
    foreach ( $Node->getData() as $key => $value )
    {
      $type = null;
      $value = $this->prepareForDb( $value, $type );
      $insertStatement->execute( [ $Id, strval( $key ), $value, $type ] );
    }
  }

  protected function updateNode( ChangeLog $ChangeLog )
  {
    // get id
    $stmt = $this->getPdo()->prepare( 'select Id from Nodes where Uuid = :Uuid' );
    $stmt->execute( [ ':Uuid' => $ChangeLog->getNode()->getUuid() ] );
    $Id = $stmt->fetchColumn();
    // do the update
    $insertStatement = $this->getPdo()->prepare( 'insert into NodeData (NodeId, Key, Value, Type) values(?,?,?,?);' );
    $update = $this->getPdo()->prepare( 'update NodeData set Value = ?, Type = ? where NodeId= ? and Key = ?;' );
    $delete = $this->getPdo()->prepare( 'delete from NodeData where NodeId= ? and Key = ?;' );
    $data = $ChangeLog->getNode()->getData();
    // write data according to change log
    for ( $i = $ChangeLog->getNextIndex(); $i < $ChangeLog->getSize(); $i++ )
    {
      $key = strval( $ChangeLog->getKey( $i ) );
      $type = null;
      $value = isset( $data[ $key ] ) ? $this->prepareForDb( $data[ $key ], $type ) : null;

      if ( $ChangeLog->isAddedEvent( $i ) )
        $insertStatement->execute( [ $Id, $key, $value, $type ] );
      if ( $ChangeLog->isModifiedEvent( $i ) )
        $update->execute( [ $value, $type, $Id, $key ] );
      if ( $ChangeLog->isDeletedEvent( $i ) )
        $delete->execute( [ $Id, $key ] );
    }
  }

  protected function prepareForDb( $value, &$type = self::STRING )
  {
    $type = self::STRING;
    if ( $value instanceof interfaces\UuidProvider )
    {
      $value = $value->getUuid();
      $type = self::UUID;
    }
    else if ( is_object( $value ) || is_array( $value ) )
    {
      $value = serialize( $value );
      $type = self::SERIALIZED_OBJECT;
    }
    return $value;
  }

  protected function recoverFromDb( $value, $type )
  {
    if ( $type == self::UUID )
      return new NodeRef( $value, $this );
    else if ( $type == self::SERIALIZED_OBJECT )
      return unserialize( $value );
    else
      return $value;
  }

  protected function loadNode( $Uuid )
  {
    $stmt = $this->getPdo()->prepare( 'select Id, Fqcn from Nodes where Uuid = :Uuid' );
    $stmt->execute( [ ':Uuid' => $Uuid ] );
    $Row = $stmt->fetch();
    if ( $Row === false )
      return null;
    $Id = $Row[ "Id" ];
    $Fqcn = $Row[ "Fqcn" ];

    $stmt = $this->getPdo()->prepare( 'select Key, Value, Type from NodeData where NodeId = :NodeId;' );
    $stmt->execute( [ ':NodeId' => $Id ] );
    $DataArray = [];
    while ( $row = $stmt->fetch( \PDO::FETCH_ASSOC ) )
    {
      $type = $row[ "Type" ];
      $val = $row[ "Value" ];
      $key = $row[ "Key" ];

      $DataArray[ $key ] = $this->recoverFromDb( $val, $type );
    }
    $Node = new $Fqcn( $DataArray, $Uuid );

    return $Node;
  }

  /**
   * 
   * @return \PDO
   */
  protected function getPdo()
  {
    if ( $this->Pdo == null )
    {
      $CreateDatabase = file_exists( $this->SqliteFile ) == false;
      $this->Pdo = new \PDO( "sqlite:" . $this->SqliteFile );
      $this->Pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
      if ( $CreateDatabase )
        $this->Pdo->exec( "BEGIN TRANSACTION;" .
            "CREATE TABLE IF NOT EXISTS `Nodes` (" .
            "	`Id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT," .
            "	`Uuid`	TEXT NOT NULL UNIQUE," .
            "	`Fqcn`	TEXT NOT NULL," .
            "	`ModifiedTime`	INTEGER" .
            ");" .
            "CREATE TABLE IF NOT EXISTS `NodeData` (" .
            "	`NodeId`	INTEGER," .
            "	`Key`	TEXT," .
            "	`Value`	TEXT," .
            "	`Type`	INTEGER NOT NULL," .
            "  PRIMARY KEY (NodeId, Key)," .
            "  FOREIGN KEY(NodeId) REFERENCES Nodes(Id)" .
            ");" .
            "COMMIT;" );
    }
    return $this->Pdo;
  }

  /**
   *
   * @var string
   */
  protected $SqliteFile;

  /**
   *
   * @var array
   */
  protected $ChangeLogs = [];

  /**
   *
   * @var \PDO
   */
  protected $Pdo;

}
