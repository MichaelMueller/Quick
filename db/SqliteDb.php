<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class SqliteDb extends abstracts\NodeDb
{

  function __construct( $SqliteFile )
  {
    $this->SqliteFile = $SqliteFile;
  }

  public function commit()
  {
    /* @var $Node interfaces\Node */
    foreach ( $this->Nodes as $Node )
    {
      // only store modified nodes
      if ( $Node->getModifiedTime() < $this->LastCommitTime )
        continue;

      // try to load node and merge the data      
      $stmt1 = $this->getPdo()->prepare( 'insert into Nodes (Uuid, ModifiedTime, Fqcn) values(?,?,?);' );
      $stmt2 = $this->getPdo()->prepare( 'insert into NodeData (NodeId, Key, Value, Type) values(?,?,?,?);' );
      $RecentNode = $this->loadNode( $Node->getUuid() );
      $DataArray = array_merge( $RecentNode ? $RecentNode->getData() : [], $Node->getData() );

      if ( $RecentNode )
      {
        $stmt = $this->getPdo()->prepare( 'select Id from Nodes where Uuid = :Uuid' );
        $stmt->execute( [ ':Uuid' => $Node->getUuid() ] );
        $Id = $stmt->fetchColumn();
      }
      else
      {
        $stmt1->execute( [ $Node->getUuid(), $Node->getModifiedTime(), get_class( $Node ) ] );
        $Id = $this->getPdo()->lastInsertId();
      }
      $this->Pdo->exec( "delete from NodeData where NodeId = " . $Id );
      foreach ( $DataArray as $key => $value )
      {
        $type = "s";
        if ( $value instanceof interfaces\UuidProvider )
        {
          $value = $value->getUuid();
          $type = "u";
        }
        else if ( is_int( $value ) )
          $type = "i";
        else if ( is_float( $value ) )
          $type = "f";
        else if ( is_bool( $value ) )
          $type = "b";
        else if ( is_object( $value ) || is_array( $value ) )
        {
          $type = "o";
          $value = serialize( $value );
        }
        $stmt2->execute( [ $Id, strval( $key ), $value, $type ] );
      }
    }
    $this->LastCommitTime = time();
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
        return $Node;
      }
    }
    return null;
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
      if ( $type == "i" )
        $val = intval( $val );
      else if ( $type == "f" )
        $val = floatval( $val );
      else if ( $type == "b" )
        $val = boolval( $val );
      else if ( $type == "o" )
        $val = unserialize( $val );
      else if ( $type == "u" )
        $val = new NodeRef( $val, $this );

      $DataArray[ $row[ "Key" ] ] = $val;
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
            "	`Type`	TEXT NOT NULL," .
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
   * @var int
   */
  protected $LastCommitTime = 0;

  /**
   *
   * @var \PDO
   */
  protected $Pdo;

}
