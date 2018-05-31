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
  }

  public function commit()
  {
    foreach ( $this->Objects as $Object )
    {
      $Hash = spl_object_hash( $Object );
      $Schema = $this->ObjectDbSchema->getObjectSchema( $Object->getFqcn() );
      $Data = $Object->getData();
      if ( !isset( $this->KnownModifiedTimes[ $Hash ] ) )
      {
        $this->ObjectDb->insert( $Schema->getSqlTableName(), $Schema->getPropertyNames( true ), $Schema->prepare( $Data, $Object->getModifiedTime(), $Object->getUuid() ) );
        $this->KnownModifiedTimes[ $Hash ] = $Object->getModifiedTime();
      }
      else if ( $this->compare( $this->KnownModifiedTimes[ $Hash ], $Object->getModifiedTime() ) < 0 )
      {
        $Exp = new \qck\Expressions\UuidEquals( $Object->getUuid(), $Schema->getUuidPropertyName() );
        $this->ObjectDb->update( $Schema->getSqlTableName(), $Schema->getPropertyNames( false ), $Schema->prepare( $Data, $Object->getModifiedTime() ), $Exp );
        $this->KnownModifiedTimes[ $Hash ] = $Object->getModifiedTime();
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
    $ModifiedTimePropName = $Schema->getModifiedTimePropertyName();
    $ModifiedTime = -1;
    // if we have an object, check if we need to load it using the ModifiedTime
    if ( $Object )
    {
      $Select->setColumns( [ $ModifiedTimePropName ] );
      $Data = $this->ObjectDb->select( $Select )->fetch( \PDO::FETCH_ASSOC );
      if ( $Data !== false )
        $ModifiedTime = $Data[ $ModifiedTimePropName ];
      if ( $this->compare( $ModifiedTime, $Object->getModifiedTime() ) <= 0 )
        return $Object;
    }
    // Load object (No prior object available or ModifiedTime changed)
    $Select->setColumns( $Schema->getPropertyNames( false ) );
    $Data = $this->ObjectDb->select( $Select )->fetch( \PDO::FETCH_ASSOC );

    if ( $Data !== false )
    {
      $Object = $Object ? $Object : new $Fqcn( $Uuid );

      $Object->setData( $Schema->recover( $Data, $this, $ModifiedTime ) );
      $Object->setModifiedTime( $ModifiedTime );
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
      if ( isset( $this->KnownModifiedTimes[ $Hash ] ) )
        unset( $this->KnownModifiedTimes[ $Hash ] );
    }
  }

  /**
   * 
   * @param string $time1
   * @param string $time2
   * @return int < 0 if $time1 is less than $time2; > 0 if $time1 is greater than $time2, and 0 if they are equal.
   */
  protected function compare( $time1, $time2 )
  {
    list($time1_usec, $time1_sec) = explode( " ", $time1 );
    list($time2_usec, $time2_sec) = explode( " ", $time2 );
    $sec1 = intval( $time1_sec );
    $sec2 = intval( $time2_sec );
    if ( $sec1 == $sec2 )
    {
      $msec1 = floatval( $time1_usec );
      $msec2 = floatval( $time2_usec );
      if ( $msec1 == $msec2 )
        return 0;
      return $msec1 < $msec2 ? -1 : 1;
    }
    else
      return $sec1 < $sec2 ? -1 : 1;
  }

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
  protected $KnownModifiedTimes = [];

}
