<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class SqlObjectDb implements Interfaces\ObjectDb
{

  function __construct( \qck\Sql\Interfaces\Db $SqlDb,
                        Interfaces\ObjectDbSchema $ObjectDbSchema )
  {
    $this->SqlDb = $SqlDb;
    $this->ObjectDbSchema = $ObjectDbSchema;
  }

  public function commit()
  {
    if ( !$this->SqlDb->isInTransaction() )
      $this->SqlDb->beginTransaction();

    /* @var $ObjectInfo ObjectInfo */
    foreach ( $this->ObjectInfos as $Uuid => $ObjectInfo )
    {
      $Schema = $this->ObjectDbSchema->getObjectSchema( $ObjectInfo->getUuidProvider()->getFqcn() );
      $UuidPropName = $Schema->getUuidPropertyName();
      $Exp = new \qck\Expressions\UuidEquals( $Uuid, $UuidPropName );
      $TableName = $Schema->getSqlTableName();
      $ItemsTableName = $Schema instanceof Interfaces\ObjectSetSchema ? $Schema->getItemsSqlTableName() : null;
      if ( $ItemsTableName )
        $this->SqlDb->delete( $ItemsTableName, $Exp );
      if ( $ObjectInfo->shouldBeDeletedOnCommit() )
      {
        $this->SqlDb->delete( $TableName, $Exp );
        unset( $this->ObjectInfos[ $Uuid ] );
      }
      else
      {
        /* @var $Object Object */
        $Object = $ObjectInfo->getUuidProvider();
        $Data = $Object->getData();
        if ( $ObjectInfo->getLastKnownModifiedTime() === null )
        {
          $this->SqlDb->insert( $TableName, $Schema->getPropertyNames( true ), $Schema->prepare( $Data, true, true ) );
        }
        else if ( $Object->getModifiedTime() != $ObjectInfo->getLastKnownModifiedTime() )
        {
          $this->SqlDb->update( $TableName, $Schema->getPropertyNames( false ), $Schema->prepare( $Data, true, false ), $Exp );
        }
        if ( $ItemsTableName )
        {
          /* @var $Object ObjectSet */
          $ObjectUuidPropName = $Schema->getObjectUuidPropertyName();
          for ( $i = 0; $i < $Object->size(); $i++ )
            $this->SqlDb->insert( $ItemsTableName, [ $UuidPropName, $ObjectUuidPropName ], [ $Object->getUuid(), $Object->at( $i )->getUuid() ] );
        }
        $ObjectInfo->updateObjectData();
      }
    }

    $this->SqlDb->commit();
  }

  public function deleteOnCommitWhere( $Fqcn, Interfaces\Expression $Expression )
  {
    $Uuids = $this->select( $Fqcn, $Expression );
    for ( $i = 0; $i < count( $Uuids ); $i++ )
      $this->delete( $Fqcn, $Uuids[ $i ] );
  }

  public function deleteOnCommit( $Fqcn, $Uuid )
  {
    if ( !isset( $this->ObjectInfos[ $Uuid ] ) )
      $this->ObjectInfos[ $Uuid ] = new ObjectInfo( new UuidProvider( $Fqcn, $Uuid ) );
    else
      $this->ObjectInfos[ $Uuid ]->setDeleteOnCommit( true );
  }

  public function load( $Fqcn, $Uuid )
  {
    $Schema = $this->ObjectDbSchema->getObjectSchema( $Fqcn );
    $Exp = new \qck\Expressions\UuidEquals( $Uuid, $Schema->getUuidPropertyName() );
    $Select = new \qck\Sql\Select( $Schema->getSqlTableName(), $Exp );
    $Select->setColumns( $Schema->getPropertyNames( true ) );
    $Data = $this->SqlDb->select( $Select )->fetch( \PDO::FETCH_ASSOC );
    if ( $Data !== false )
    {
      /* @var $ObjectInfo ObjectInfo */
      $ObjectInfo = isset( $this->ObjectInfos[ $Uuid ] ) ? $this->ObjectInfos[ $Uuid ] : null;
      $Object = $ObjectInfo ? $ObjectInfo->getUuidProvider() : new $Fqcn();
      $Object->setData( $Schema->recover( $Data, $this ) );
      if ( $ObjectInfo )
        $ObjectInfo->updateObjectData();
      else
        $this->ObjectInfos[ $Uuid ] = new ObjectInfo( $Object );
      $ItemsTableName = $Schema instanceof Interfaces\ObjectSetSchema ? $Schema->getItemsSqlTableName() : null;
      if ( $ItemsTableName )
      {
        $Select = new \qck\Sql\Select( $ItemsTableName, $Exp );
        $ObjUuidPropName = $Schema->getObjectUuidPropertyName();
        $Select->setColumns( [ $ObjUuidPropName ] );
        $Results = $this->SqlDb->select( $Select )->fetchAll( \PDO::FETCH_ASSOC );
        foreach ( $Results as $Result )
          $Object->add( new LazyLoader( $Object->getObjectFqcn(), $Result[ $ObjUuidPropName ], $this ) );
      }

      return $Object;
    }

    return null;
  }

  public function select( $Fqcn, \qck\Expressions\Interfaces\Expression $Expression,
                          $Limit = null, $Offset = null, $OrderPropName = null,
                          $Descending = true )
  {
    $LazyLoaders = [];
    $Schema = $this->ObjectDbSchema->getObjectSchema( $Fqcn );
    $UuidPropName = $Schema->getUuidPropertyName();
    $Select = new \qck\Sql\Select( $Schema->getSqlTableName(), $Expression );
    $Select->setLimit( $Limit );
    $Select->setOffset( $Offset );
    $Select->setOrderParams( $OrderPropName, $Descending );
    $Select->setColumns( [ $UuidPropName ] );
    $Results = $this->SqlDb->select( $Select )->fetchAll( \PDO::FETCH_ASSOC );
    foreach ( $Results as $Result )
      $LazyLoaders[] = new LazyLoader( $Fqcn, $Result[ $UuidPropName ], $this );
    return $LazyLoaders;
  }

  public function register( Interfaces\Object $Object )
  {
    $this->registerRecursively( $Object );
  }

  protected function registerRecursively( Interfaces\Object $Object, array &$Visited = [] )
  {
    if ( in_array( $Object, $Visited, true ) )
      return;
    $Visited[] = $Object;

    $Uuid = $Object->getUuid();
    if ( !isset( $this->ObjectInfos[ $Uuid ] ) )
      $this->ObjectInfos[ $Uuid ] = new ObjectInfo( $Object );

    foreach ( $Object->getData() as $value )
      if ( $value instanceof Interfaces\Object )
        $this->registerRecursively( $value, $Visited );
  }

  function getSqlDb()
  {
    return $this->SqlDb;
  }

  /**
   *
   * @var \qck\Sql\Interfaces\Db
   */
  protected $SqlDb;

  /**
   *
   * @var Interfaces\ObjectDbSchema
   */
  protected $ObjectDbSchema;

  /**
   *
   * @var array
   */
  protected $ObjectInfos = [];

}
