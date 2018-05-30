<?php

namespace qck\Sql\Abstracts;

/**
 *
 * @author muellerm
 */
abstract class Db implements \qck\Sql\Interfaces\Db, \qck\Sql\Interfaces\DbSchema, \qck\Sql\Interfaces\DbDictionary
{

  /**
   * @return \PDO
   */
  abstract protected function getPdo();

  public function beginTransaction()
  {
    $this->getPdo()->beginTransaction();
  }

  public function commit()
  {
    $this->getPdo()->commit();
  }

  function install( \qck\Sql\Interfaces\Schema $Schema, $DropTables = false )
  {
    foreach ( $Schema->getTables() as $Table )
      $this->createTable( $Table, true, $DropTables );
  }

  public function createTable( \qck\Sql\Interfaces\Table $Table, $IfNotExists = true,
                               $DropIfExists = false )
  {
    $Sql = $DropIfExists ? "DROP TABLE IF EXISTS " . $Table->getName() . ";" : "";
    $Sql .= "CREATE TABLE" . ($IfNotExists ? " IF NOT EXISTS" : "") . " " . $Table->getName() . " ( ";
    $Sql .= $Table->getColumnSql( $this->getDbDictionary() ) . ");";
    foreach ( $Table->getUniqueIndexes() as $ColName )
      $Sql .= "CREATE UNIQUE INDEX " . $ColName . "_UniqueIndex  ON " . $Table->getName() . " (" . $ColName . ");";
    foreach ( $Table->getIndexes() as $ColName )
      $Sql .= "CREATE INDEX " . $ColName . "_Index  ON " . $Table->getName() . " (" . $ColName . ");";
    $this->getPdo()->exec( $Sql );
  }

  public function dropTable( $TableName )
  {
    $this->getPdo()->exec( "DROP TABLE IF EXISTS " . $TableName );
  }

  public function insert( $TableName, array $ColumnNames, array $Values )
  {
    $PlaceHolder = [];
    for ( $i = 0; $i < count( $Values ); $i++ )
      $PlaceHolder[] = "?";
    $Sql = "INSERT INTO " . $TableName . " (" . implode( ", ", $ColumnNames ) . ") VALUES (" . implode( ", ", $PlaceHolder ) . ")";
    $Statement = $this->getPdo()->prepare( $Sql );
    $Statement->execute( $Values );
    return $this->Pdo->lastInsertId();
  }

  public function update( $TableName, array $ColumnNames, array $Values,
                          \qck\Expressions\Interfaces\Expression $Expression )
  {
    $ColAndPlaceHolder = [];
    foreach ( $ColumnNames as $ColName )
      $ColAndPlaceHolder[] = $ColName . " = ?";
    $Sql = "UPDATE " . $TableName . " SET " . implode( ", ", $ColAndPlaceHolder ) . " WHERE " . $Expression->toSql( $this->getDbDictionary(), $Values );
    $Statement = $this->getPdo()->prepare( $Sql );
    $Statement->execute( $Values );
    return $Statement->rowCount();
  }

  public function delete( $TableName, \qck\Expressions\Interfaces\Expression $Expression )
  {
    $Params = [];
    $Sql = "DELETE FROM " . $TableName . " WHERE " . $Expression->toSql( $this->getDbDictionary(), $Params );
    $Statement = $this->getPdo()->prepare( $Sql );
    $Statement->execute( $Params );
    return $Statement->rowCount();
  }

  /**
   * 
   * @param \qck\Sql\Interfaces\Select $Select
   * @return \PDOStatement
   */
  function select( \qck\Sql\Interfaces\Select $Select )
  {
    $Params = [];
    $Sql = $Select->toSql( $this->getDbDictionary(), $Params );
    $Statement = $this->getPdo()->prepare( $Sql );
    $Statement->execute( $Params );
    return $Statement;
  }

  public function getDbDictionary()
  {
    return $this;
  }

  public function getDbSchema()
  {
    return $this;
  }

  /**
   *
   * @var \PDO
   */
  protected $Pdo;

}
