<?php

namespace qck\Data2;

/**
 *
 * @author muellerm
 */
class Db implements Interfaces\Db
{

  function __construct( \qck\Sql\Interfaces\Db $Db, Interfaces\DbSchema $DbSchema )
  {
    $this->Db = $Db;
    $this->DbSchema = $DbSchema;
  }

  public function commit()
  {
    /* @var $Object Interfaces\Object */
    foreach ( $this->Objects as $Object )
    {
      if ( $Object->getId() === null )
      {
        $Schema = $this->DbSchema->getObjectSchema( $Object->getFqcn() );
        $Data = $Object->getData();
        $Id = $this->Db->insert( $Schema->getSqlTableName(), $Schema->getPropertyNames( false ), $Schema->prepare( $Data ) );
        $Object->setId( $Id );
        $Hash = spl_object_hash( $Object );
        $this->KnownVersions[ $Hash ] = $Object->getVersion();
      }
    }

    /* @var $Object Interfaces\Object */
    foreach ( $this->Objects as $Object )
    {
      $Hash = spl_object_hash( $Object );
      if ( $this->KnownVersions[ $Hash ] < $Object->getVersion() )
      {
        $Schema = $this->DbSchema->getObjectSchema( $Object->getFqcn() );
        $Data = $Object->getData();
        $Exp = new \qck\Expressions\IdEquals( $Object->getId(), $Schema->getIdPropertyName() );
        $this->Db->update( $Schema->getSqlTableName(), $Schema->getPropertyNames( false ), $Schema->prepare( $Data ), $Exp );
        $this->KnownVersions[ $Hash ] = $Object->getVersion();
      }
    }
  }

  public function deleteWhere( $Fqcn, Interfaces\Expression $Expression )
  {
    $Ids = $this->select( $Fqcn, $Expression );
    $i = 0;
    for ( $i = 0; $i < count( $Ids ); $i++ )
      $this->delete( $Fqcn, $Ids[ $i ] );
    return $i + 1;
  }

  public function delete( $Fqcn, $Id )
  {
    $Schema = $this->DbSchema->getObjectSchema( $Fqcn );
    $Exp = new \qck\Expressions\IdEquals( $Id, $Schema->getIdPropertyName() );
    $this->forgetObject( $Fqcn, $Id );
    return $this->Db->delete( $Schema->getSqlTableName(), $Exp );
  }

  public function load( $Fqcn, $Id )
  {
    $Schema = $this->DbSchema->getObjectSchema( $Fqcn );
    $Exp = new \qck\Expressions\IdEquals( $Id, $Schema->getIdPropertyName() );
    $Object = $this->findObject( $Fqcn, $Id );
    $Select = new \qck\Sql\Select( $Schema->getSqlTableName(), $Exp );
    $VersionPropName = $Schema->getVersionPropertyName();
    $Version = -1;
    // if we have an object, check if we need to load it using the version
    if ( $Object )
    {
      $Select->setColumns( [ $VersionPropName ] );
      $Data = $this->Db->select( $Select )->fetch( \PDO::FETCH_ASSOC );
      if ( $Data !== false )
        $Version = $Data[ $VersionPropName ];
      if ( $Object->getVersion() >= $Version )
        return $Object;
    }
    // Load object (No prior object available or version changed)
    $Select->setColumns( $Schema->getPropertyNames( false ) );
    $Data = $this->Db->select( $Select )->fetch( \PDO::FETCH_ASSOC );

    if ( $Data !== false )
    {
      $Object = $Object ? $Object : new $Fqcn();

      $Object->setData( $Schema->recover( $Data, $this, $Version, $Id ) );
      $Object->setId( $Id );
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
    $Schema = $this->DbSchema->getObjectSchema( $Fqcn );
    $IdPropName = $Schema->getIdPropName();
    $Select = new \qck\Sql\Select( $Schema->getSqlTableName(), $Expression );
    $Select->setLimit( $Limit );
    $Select->setOffset( $Offset );
    $Select->setOrderParams( $OrderPropName, $Descending );
    $Select->setColumns( [ $IdPropName ] );
    $Results = $this->Db->select( $Select )->fetchAll( \PDO::FETCH_ASSOC );
    foreach ( $Results as $Result )
      $LazyLoaders[] = new LazyLoader( $Fqcn, $Result[ $IdPropName ], $this );
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
   * @param int $Id
   * @return Interfaces\Object
   */
  protected function findObject( $Fqcn, $Id )
  {
    foreach ( $this->Objects as $Object )
      if ( $Object->getFqcn() == $Fqcn && $Object->getId() == $Id )
        return $Object;

    return null;
  }

  protected function forgetObject( $Fqcn, $Id )
  {
    $Object = $this->findObject( $Fqcn, $Id );
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
   * @var \qck\Sql\Interfaces\Db
   */
  protected $Db;

  /**
   *
   * @var Interfaces\DbSchema
   */
  protected $DbSchema;

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
