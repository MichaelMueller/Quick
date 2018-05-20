<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class StatementFactory
{

  function __construct( \PDO $Pdo, interfaces\Schema $Schema )
  {
    $this->Pdo = $Pdo;
    $this->Schema = $Schema;
  }

  /**
   * 
   * @param \qck\db\interfaces\Object $Object
   * @return \PDOStatement
   */
  function createDelete( $Fqcn, interfaces\Expression $Expression, array &$Params )
  {
    $DriverName = $this->Pdo->getAttribute( \PDO::ATTR_DRIVER_NAME );
    $query = 'delete from ' . $this->getTableName( $Fqcn ) . ' where ' . $Expression->toSql( $DriverName, $Params ) . ';';
    return $this->Pdo->prepare( $query );
  }

  /**
   * 
   * @param \qck\db\interfaces\Object $Object
   * @return \PDOStatement
   */
  function createObjectListInsert( interfaces\ObjectList $ObjectList, interfaces\Object $Object, array &$Params )
  {
    $ObjectListTableName = $this->getTableName( $ObjectList->getFqcn() );
    $ObjectTableName = $this->getTableName( $Object->getFqcn() );
    $ObjectIdColum = $this->Schema->getObjectDescriptor( $Object->getFqcn() )->getIdPropertyName();
    $query = 'insert into ' . $ObjectListTableName . ' (Id, ' . $ObjectTableName . ') values(?, ?);';

  }

  /**
   * 
   * @param \qck\db\interfaces\Object $Object
   * @return \PDOStatement
   */
  function createInsert( interfaces\Object $Object, array &$Params, &$IdColumn )
  {
    $DataWithoutId = $ColumnNamesWithoutId = [];
    $IdColumn = $Id = $FqcnTableName = null;
    $this->gatherInfo( $Object, $DataWithoutId, $ColumnNamesWithoutId, $IdColumn, $Id, $FqcnTableName );

    // build the query
    $PlaceholderArr = [];
    for ( $i = 0; $i < count( $DataWithoutId ); $i++ )
      $PlaceholderArr[] = "?";
    $query = 'insert into ' . $FqcnTableName . ' (' . implode( ", ", $ColumnNamesWithoutId ) . ') values(' . implode( ", ", $PlaceholderArr ) . ');';

    // create statement and return
    $insert = $this->Pdo->prepare( $query );
    $Params = $DataWithoutId;
    return $insert;
  }

  /**
   * 
   * @param \qck\db\interfaces\Object $Object
   * @return \PDOStatement
   */
  function createUpdate( interfaces\Object $Object, array &$Params )
  {
    $DataWithoutId = $ColumnNamesWithoutId = [];
    $IdColumn = $Id = $FqcnTableName = null;
    $this->gatherInfo( $Object, $DataWithoutId, $ColumnNamesWithoutId, $IdColumn, $Id, $FqcnTableName );

    // build the query
    $query = 'update ? set ';
    $NumColumns = count( $ColumnNamesWithoutId );
    for ( $i = 0; $i < $NumColumns; $i++ )
    {
      $query .= $ColumnNamesWithoutId[ $i ] . " = ?";
      if ( $i + 1 < $NumColumns )
        $query .= ", ";
    }
    $query .= ' where ' . $IdColumn . ' = ?';

    // create statement and return
    $update = $this->Pdo->prepare( $query );
    array_push( $DataWithoutId, $Id );
    $Params = $DataWithoutId;
    return $update;
  }

  protected function getTableName( $Fqcn )
  {
    return str_replace( "\\", "_", $Fqcn );
  }

  protected function gatherInfo( interfaces\Object $Object, array &$DataWithoutId,
                                 array &$ColumnNamesWithoutId, &$IdColumn, &$Id = null,
                                 &$FqcnTableName = null )
  {
    // retrieve object infos
    $Fqcn = $Object->getFqcn();
    $FqcnTableName = $this->getTableName( $Fqcn );
    $ObjectInfo = $this->Schema->getObjectDescriptor( $Fqcn );
    $IdColumn = $ObjectInfo->getIdPropertyName();
    $ColumnNamesWithoutId = $ObjectInfo->getPropertyNames();
    unset( $ColumnNamesWithoutId[ $IdColumn ] );

    // adapt data
    $DataWithoutId = $Object->getData();
    $Id = $DataWithoutId[ $IdColumn ];
    unset( $DataWithoutId[ $IdColumn ] );
    $DataWithoutId = array_values( $DataWithoutId );
  }

  /**
   *
   * @var \PDO
   */
  protected $Pdo;

  /**
   *
   * @var interfaces\Schema
   */
  protected $Schema;

}
