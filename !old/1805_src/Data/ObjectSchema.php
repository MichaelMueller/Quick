<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class ObjectSchema implements interfaces\ObjectSchema
{

  function __construct( $Fqcn, $Uuid, array $Properties = [] )
  {
    $this->UuidProperty = new UuidProperty();
    $this->ModifiedTimeProperty = new ModifiedTimeProperty();
    $this->Fqcn = $Fqcn;
    $this->Uuid = $Uuid;
    $this->Properties = $Properties;
    $this->addProperty( $this->UuidProperty );
    $this->addProperty( $this->ModifiedTimeProperty );
  }

  function addProperty( Abstracts\Property $Property )
  {
    $this->Properties[ $Property->getName() ] = $Property;
  }

  public function getUuidPropertyName()
  {
    return $this->UuidProperty->getName();
  }

  public function getModifiedTimePropertyName()
  {
    return $this->ModifiedTimeProperty->getName();
  }

  function getFqcn()
  {
    return $this->Fqcn;
  }

  public function getPropertyNames( $WithUuidProperty = false )
  {
    $Props = $this->Properties;
    if ( !$WithUuidProperty )
      unset( $Props[ $this->getUuidPropertyName() ] );
    return array_keys( $Props );
  }

  public function getSqlTableName()
  {
    return str_replace( "\\", "_", $this->Fqcn );
  }

  public function prepare( array $Data, $WithModifiedTime = null, $WithUuid = null )
  {
    $PreparedData = [];
    foreach ( $this->Properties as $Name => $Property )
    {
      if ( $Name == $this->getModifiedTimePropertyName() && !$WithModifiedTime )
        continue;
      else if ( $Name == $this->getUuidPropertyName() && !$WithUuid )
        continue;

      $PreparedData[] = isset( $Data[ $Name ] ) ? $Property->prepare( $Data[ $Name ] ) : null;
    }
    return $PreparedData;
  }

  public function recover( array $Data, Interfaces\Db $ObjectDb )
  {
    $RecoveredData = [];
    foreach ( $this->Properties as $Name => $Property )
      $RecoveredData[ $Name ] = isset( $Data[ $Name ] ) ? $Property->recover( $Data[ $Name ], $ObjectDb ) : null;

    return $RecoveredData;
  }

  public function applyTo( \qck\Sql\Interfaces\DbSchema $ObjectDbSchema )
  {
    $Table = new \qck\Sql\Table( $this->getSqlTableName() );
    foreach ( $this->Properties as $Property )
      $Property->applyTo( $Table );

    $ObjectDbSchema->createTable( $Table );
  }

  public function convertKeysToUuids( array $DataArray )
  {
    $Result = [];
    foreach ( $this->Properties as $Property )
      $Result[ $Property->getUuid() ] = isset( $DataArray[ $Property->getName() ] ) ? $DataArray[ $Property->getName() ] : null;
    return $Result;
  }

  public function convertUuidsToKeys( array $DataArray )
  {
    $Result = [];
    foreach ( $this->Properties as $Property )
      $Result[ $Property->getName() ] = isset( $DataArray[ $Property->getUuid() ] ) ? $DataArray[ $Property->getUuid() ] : null;
    return $Result;
  }

  public function filterArray( array $DataArray )
  {
    $Result = [];
    foreach ( $this->Properties as $Property )
      $Result[ $Property->getName() ] = isset( $DataArray[ $Property->getName() ] ) ? $DataArray[ $Property->getName() ] : null;
    return $Result;
  }

  public function getUuid()
  {
    return $this->Uuid;
  }

  /**
   *
   * @var UuidProperty
   */
  protected $UuidProperty;

  /**
   *
   * @var ModifiedTimeProperty
   */
  protected $ModifiedTimeProperty;

  /**
   *
   * @var string
   */
  protected $Fqcn;

  /**
   *
   * @var string
   */
  protected $Uuid;

  /**
   *
   * @var array
   */
  protected $Properties;

}
