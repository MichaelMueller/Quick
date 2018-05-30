<?php

namespace qck\Data2;

/**
 *
 * @author muellerm
 */
class ObjectSchema implements interfaces\ObjectSchema
{

  function __construct( $Fqcn, array $Properties = [] )
  {
    $this->IdProperty = new IdProperty();
    $this->VersionProperty = new VersionProperty();
    $this->Fqcn = $Fqcn;
    $this->Properties = $Properties;
    $this->addProperty( $this->IdProperty );
    $this->addProperty( $this->VersionProperty );
  }

  function addProperty( Abstracts\Property $Property )
  {
    $this->Properties[ $Property->getName() ] = $Property;
  }

  public function getIdPropertyName()
  {
    return $this->IdProperty->getName();
  }

  public function getVersionPropertyName()
  {
    return $this->VersionProperty->getName();
  }

  function getFqcn()
  {
    return $this->Fqcn;
  }

  public function getPropertyNames( $WithIdProperty = false )
  {
    $Props = $this->Properties;
    if ( !$WithIdProperty )
      unset( $Props[ $this->getIdPropertyName() ] );
    return array_keys( $Props );
  }

  public function getSqlTableName()
  {
    return str_replace( "\\", "_", $this->Fqcn );
  }

  public function prepare( array $Data, $Version = null, $Id = null )
  {
    $PreparedData = [];
    foreach ( $this->Properties as $Name => $Property )
    {
      if ( $Name == $this->getIdPropertyName() && $Id )
        $PreparedData[ $Name ] = $Property->prepare( $Id );
      else if ( $Name == $this->getVersionPropertyName() && $Version )
        $PreparedData[ $Name ] = $Property->prepare( $Version );
      else
        $PreparedData[ $Name ] = isset( $Data[ $Name ] ) ? $Property->prepare( $Data[ $Name ] ) : null;
    }
    return $PreparedData;
  }

  public function recover( array $Data, Interfaces\Db $Db, &$Version = null, &$Id = null )
  {
    $RecoveredData = [];
    foreach ( $this->Properties as $Name => $Property )
    {
      $Value = isset( $Data[ $Name ] ) ? $Property->recover( $Data[ $Name ], $Db ) : null;
      if ( $Name == $this->getIdPropertyName() )
        $Id = $Value;
      else if ( $Name == $this->getVersionPropertyName() )
        $Version = $Value;
      else
        $RecoveredData[ $Name ] = $Value;
    }
    return $RecoveredData;
  }

  public function applyTo( \qck\Sql\Interfaces\DbSchema $DbSchema )
  {
    $Table = new \qck\Sql\Table( $this->getSqlTableName() );
    foreach ( $this->Properties as $Property )
      $Property->applyTo( $Table );

    $DbSchema->createTable( $Table );
  }

  /**
   *
   * @var IdProperty
   */
  protected $IdProperty;

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
