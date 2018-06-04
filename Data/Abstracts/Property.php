<?php

namespace qck\Data\Abstracts;

/**
 *
 * @author muellerm
 */
abstract class Property
{

  /**
   * @return mixed
   */
  abstract function prepare( $Value );

  /**
   * @return mixed
   */
  abstract function recover( $Value, \qck\Data\Interfaces\ObjectDb $ObjectDb );

  /**
   * @return \qck\Sql\Interfaces\Column
   */
  abstract function toSqlColumn();

  /**
   * create the appropriate control on a form
   */
  // abstract function applyToForm( \qck\Data\Interfaces\Form $Form );

  /**
   * 
   */
  function applyTo( \qck\Sql\Interfaces\Table $Table )
  {
    $Table->addColumn( $this->toSqlColumn() );
    if ( $this->Unique )
      $Table->addUniqueIndex( $this->getName() );
    if ( $this->Index )
      $Table->addIndex( $this->getName() );
  }

  function __construct( $Name, $Uuid )
  {
    $this->Name = $Name;
    $this->Uuid = $Uuid;
  }

  function getName()
  {
    return $this->Name;
  }

  function getUuid()
  {
    return $this->Uuid;
  }

  function setUnique()
  {
    $this->Unique = true;
    $this->Index = false;
  }

  function setIndex()
  {
    $this->Unique = false;
    $this->Index = true;
  }

  protected $Name;
  protected $Uuid;
  protected $Unique = false;
  protected $Index = false;

}
