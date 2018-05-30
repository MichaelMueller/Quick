<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class ObjectDb implements Interfaces\ObjectDb
{

  function __construct( \qck\Sql\Interfaces\Db $ObjectDb,
                        Interfaces\ObjectDbSchema $ObjectDbSchema )
  {
    $this->ObjectDb = $ObjectDb;
    $this->ObjectDbSchema = $ObjectDbSchema;
    $this->KeyPool = $this->load( KeyPool::class, 1 );
    if ( !$this->KeyPool )
    {
      $this->KeyPool = new KeyPool();
      $this->register( $this->KeyPool );
    }
  }

  public function commit()
  {
    /* @var $Object Interfaces\Object */
    foreach ( $this->Objects as $Object )
    {
      if ( $Object->getUuid() === null )
      {
        $Object->setUuid( $this->KeyPool->getNextKey() );
      }
    }

    foreach ( $this->Objects as $Object )
    {
      $Hash = spl_object_hash( $Object );
      $Schema = $this->ObjectDbSchema->getObjectSchema( $Object->getFqcn() );
      $Data = $Object->getData();
      if ( !isset( $this->KnownVersions[ $Hash ] ) )
      {
        $this->ObjectDb->insert( $Schema->getSqlTableName(), $Schema->getPropertyNames( true ), $Schema->prepare( $Data, $Object->getVersion(), $Object->getUuid() ) );
        $this->KnownVersions[ $Hash ] = $Object->getVersion();
      }
      else if ( $this->KnownVersions[ $Hash ] < $Object->getVersion() )
      {
        $Exp = new \qck\Expressions\UuidEquals( $Object->getUuid(), $Schema->getUuidPropertyName() );
        $this->ObjectDb->update( $Schema->getSqlTableName(), $Schema->getPropertyNames( false ), $Schema->prepare( $Data, $Object->getVersion() ), $Exp );
        $this->KnownVersions[ $Hash ] = $Object->getVersion();
      }
    }
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
    $this->forgetObject( $Fqcn, $Uuid );
    return $this->ObjectDb->delete( $Schema->getSqlTableName(), $Exp );
  }

  public function load( $Fqcn, $Uuid )
  {
    $Schema = $this->ObjectDbSchema->getObjectSchema( $Fqcn );
    $Exp = new \qck\Expressions\UuidEquals( $Uuid, $Schema->getUuidPropertyName() );
    $Object = $this->findObject( $Fqcn, $Uuid );
    $Select = new \qck\Sql\Select( $Schema->getSqlTableName(), $Exp );
    $VersionPropName = $Schema->getVersionPropertyName();
    $Version = -1;
    // if we have an object, check if we need to load it using the version
    if ( $Object )
    {
      $Select->setColumns( [ $VersionPropName ] );
      $Data = $this->ObjectDb->select( $Select )->fetch( \PDO::FETCH_ASSOC );
      if ( $Data !== false )
        $Version = $Data[ $VersionPropName ];
      if ( $Object->getVersion() >= $Version )
        return $Object;
    }
    // Load object (No prior object available or version changed)
    $Select->setColumns( $Schema->getPropertyNames( false ) );
    $Data = $this->ObjectDb->select( $Select )->fetch( \PDO::FETCH_ASSOC );

    if ( $Data !== false )
    {
      $Object = $Object ? $Object : new $Fqcn();

      $Object->setData( $Schema->recover( $Data, $this, $Version ) );
      $Object->setUuid( $Uuid );
      $Object->setVersion( $Version );
      $this->register( $Object );
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
    $Results = $this->ObjectDb->select( $Select )->fetchAll( \PDO::FETCH_ASSOC );
    foreach ( $Results as $Result )
      $LazyLoaders[] = new LazyLoader( $Fqcn, $Result[ $UuidPropName ], $this );
    return $LazyLoaders;
  }

  protected function registerRecursively( Interfaces\Object $Object, array &$Visited = [] )
  {
    if ( in_array( $Object, $Visited, true ) )
      return;
    $Visited[] = $Object;

    $Hash = spl_object_hash( $Object );
    if ( !isset( $this->Objects[ $Hash ] ) )
      $this->Objects[ $Hash ] = $Object;

    foreach ( $Object->getData() as $value )
      if ( $value instanceof Interfaces\Object )
        $this->registerRecursively( $value, $Visited );
  }

  /**
   * 
   * @param string $Fqcn
   * @param int $Uuid
   * @return Interfaces\Object
   */
  protected function findObject( $Fqcn, $Uuid )
  {
    foreach ( $this->Objects as $Object )
      if ( $Object->getFqcn() == $Fqcn && $Object->getUuid() == $Uuid )
        return $Object;

    return null;
  }

  protected function forgetObject( $Fqcn, $Uuid )
  {
    $Object = $this->findObject( $Fqcn, $Uuid );
    if ( $Object )
    {
      $Hash = spl_object_hash( $Object );
      unset( $this->Objects[ $Hash ] );
      if ( isset( $this->KnownVersions[ $Hash ] ) )
        unset( $this->KnownVersions[ $Hash ] );
    }
  }

  /**
   *
   * @var KeyPool 
   */
  protected $KeyPool;

  /**
   *
   * @var \qck\Sql\Interfaces\Db
   */
  protected $ObjectDb;

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
  protected $KnownVersions = [];

}
