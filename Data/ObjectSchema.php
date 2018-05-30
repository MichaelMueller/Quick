<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class ObjectSchema implements interfaces\ObjectSchema
{

  function __construct( $Fqcn, array $Properties = [] )
  {
    $this->UuidProperty = new UuidProperty();
    $this->VersionProperty = new VersionProperty();
    $this->Fqcn = $Fqcn;
    $this->Properties = $Properties;
    $this->addProperty( $this->UuidProperty );
    $this->addProperty( $this->VersionProperty );
  }

  function addProperty( Abstracts\Property $Property )
  {
    $this->Properties[ $Property->getName() ] = $Property;
  }

  public function getUuidPropertyName()
  {
    return $this->UuidProperty->getName();
  }

  public function getVersionPropertyName()
  {
    return $this->VersionProperty->getName();
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

  public function prepare( array $Data, $Version = null, $Uuid = null )
  {
    $PreparedData = [];
    foreach ( $this->Properties as $Name => $Property )
    {
      if ( $Name == $this->getVersionPropertyName() )
      {
        if ( $Version === null )
          continue;
        $PreparedData[] = $Property->prepare( $Version );
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

  public function recover( array $Data, Interfaces\ObjectDb $ObjectDb, &$Version = null,
                           &$Uuid = null )
  {
    $RecoveredData = [];
    foreach ( $this->Properties as $Name => $Property )
    {
      $Value = isset( $Data[ $Name ] ) ? $Property->recover( $Data[ $Name ], $ObjectDb ) : null;
      if ( $Name == $this->getUuidPropertyName() )
        $Uuid = $Value;
      else if ( $Name == $this->getVersionPropertyName() )
        $Version = $Value;
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

  /**
   *
   * @var UuidProperty
   */
  protected $UuidProperty;

  /**
   *
   * @var VersionProperty
   */
  protected $VersionProperty;

  /**
   *
   * @var string
   */
  protected $Fqcn;

  /**
   *
   * @var array
   */
  protected $Properties;

}
