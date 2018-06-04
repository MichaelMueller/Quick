<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class ObjectDb implements Interfaces\ObjectDb
{

  function __construct( \qck\Sql\Interfaces\Db $SqlDb,
                        Interfaces\ObjectDbSchema $ObjectDbSchema )
  {
    $this->SqlDb = $SqlDb;
    $this->ObjectDbSchema = $ObjectDbSchema;
  }

  public function commit()
  {
    $this->SqlDb->commit();
    /* @var $Updater ObjectUpdater */
    foreach ( $this->ObjectUpdaters as $Updater )
      $Updater->setChangesCommited();
  }

  public function deleteWhere( $Fqcn, Interfaces\Expression $Expression )
  {
    $Uuids = $this->select( $Fqcn, $Expression );
    $i = 0;
    for ( $i = 0; $i < count( $Uuids ); $i++ )
      $this->delete( $Fqcn, $Uuids[ $i ] );
    return $i + 1;
  }

  public function delete( $Fqcn, $Uuid )
  {
    $Schema = $this->ObjectDbSchema->getObjectSchema( $Fqcn );
    $Exp = new \qck\Expressions\UuidEquals( $Uuid, $Schema->getUuidPropertyName() );
    if ( !$this->SqlDb->isInTransaction() )
      $this->SqlDb->beginTransaction();
    $this->SqlDb->delete( $Schema->getSqlTableName(), $Exp );
    if ( isset( $this->Objects[ $Uuid ] ) )
    {
      $this->Objects[ $Uuid ]->removeObserver( $this->ObjectUpdaters[ $Uuid ] );
      unset( $this->Objects[ $Uuid ] );
      unset( $this->ObjectUpdaters[ $Uuid ] );
    }
  }

  public function load( $Fqcn, $Uuid )
  {
    /* @var $Updater ObjectUpdater */
    $Updater = null;
    if ( isset( $this->ObjectUpdaters[ $Uuid ] ) )
    {
      $Updater = $this->ObjectUpdaters[ $Uuid ];
      if ( $Updater->hasUncomittedChanges() )
        return $this->Objects[ $Uuid ];
    }

    $Schema = $this->ObjectDbSchema->getObjectSchema( $Fqcn );
    $Exp = new \qck\Expressions\UuidEquals( $Uuid, $Schema->getUuidPropertyName() );
    $Select = new \qck\Sql\Select( $Schema->getSqlTableName(), $Exp );
    $Select->setColumns( $Schema->getPropertyNames( false ) );
    $Data = $this->SqlDb->select( $Select )->fetch( \PDO::FETCH_ASSOC );
    if ( $Data !== false )
    {
      $Object = $Updater ? $this->Objects[ $Uuid ] : new $Fqcn( $Uuid );
      $ModifiedTime = null;
      $Object->setData( $Schema->recover( $Data, $this, $ModifiedTime ) );
      $Object->setModifiedTime( $ModifiedTime );
      if ( !$Updater )
        $this->registerAndAddUpdater( $Object, $Schema, true );
      else
        $Updater->setChangesCommited();
      return $Object;
    }

    return null;
  }

  public function register( Interfaces\Object $Object )
  {
    $this->registerRecursively( $Object );
  }

  public function select( $Fqcn, \qck\Expressions\Interfaces\Expression $Expression,
                          $Limit = null, $Offset = null, $OrderPropName = null,
                          $Descending = true )
  {
    $LazyLoaders = [];
    $Schema = $this->ObjectDbSchema->getObjectSchema( $Fqcn );
    $UuidPropName = $Schema->getUuidPropName();
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

  protected function registerRecursively( Interfaces\Object $Object, array &$Visited = [] )
  {
    if ( in_array( $Object, $Visited, true ) )
      return;
    $Visited[] = $Object;

    if ( !isset( $this->ObjectUpdaters[ $Object->getUuid() ] ) )
    {
      $Schema = $this->ObjectDbSchema->getObjectSchema( $Object->getFqcn() );
      $Data = $Object->getData();

      if ( !$this->SqlDb->isInTransaction() )
        $this->SqlDb->beginTransaction();
      $this->SqlDb->insert( $Schema->getSqlTableName(), $Schema->getPropertyNames( true ), $Schema->prepare( $Data, $Object->getModifiedTime(), $Object->getUuid() ) );
      $this->registerAndAddUpdater( $Object, $Schema );
    }

    foreach ( $Object->getData() as $value )
      if ( $value instanceof Interfaces\Object )
        $this->registerRecursively( $value, $Visited );
  }

  protected function registerAndAddUpdater( Interfaces\Object $Object,
                                            Interfaces\ObjectSchema $Schema,
                                            $LoadedObject = false )
  {
    $Uuid = $Object->getUuid();
    $this->Objects[ $Uuid ] = $Object;
    $Updater = new ObjectUpdater( $Schema, $this->SqlDb );
    if ( $LoadedObject )
      $Updater->setChangesCommited();
    $Object->addObserver( $Updater );
    $this->ObjectUpdaters[ $Uuid ] = $Updater;
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
  protected $Objects = [];

  /**
   *
   * @var array
   */
  protected $ObjectUpdaters = [];

}
