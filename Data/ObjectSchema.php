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

  public function prepare( array $Data, $ModifiedTime = null, $Uuid = null )
  {
    $PreparedData = [];
    foreach ( $this->Properties as $Name => $Property )
    {
      if ( $Name == $this->getModifiedTimePropertyName() )
      {
        if ( $ModifiedTime === null )
          continue;
        $PreparedData[] = $Property->prepare( $ModifiedTime );
      }
      else if ( $Name == $this->getUuidPropertyName() )
      {
        if ( $Uuid === null )
          continue;
        $PreparedData[] = $Property->prepare( $Uuid );
      }
      else
        $PreparedData[] = isset( $Data[ $Name ] ) ? $Property->prepare( $Data[ $Name ] ) : null;
    }
    return $PreparedData;
  }

  public function recover( array $Data, Interfaces\ObjectDb $ObjectDb,
                           &$ModifiedTime = null, &$Uuid = null )
  {
    $RecoveredData = [];
    foreach ( $this->Properties as $Name => $Property )
    {
      $Value = isset( $Data[ $Name ] ) ? $Property->recover( $Data[ $Name ], $ObjectDb ) : null;
      if ( $Name == $this->getUuidPropertyName() )
        $Uuid = $Value;
      else if ( $Name == $this->getModifiedTimePropertyName() )
        $ModifiedTime = $Value;
      else
        $RecoveredData[ $Name ] = $Value;
    }
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
