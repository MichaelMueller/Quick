<?php

namespace qck\Data2\Abstracts;

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
  abstract function recover( $Value, \qck\Data2\Interfaces\Db $Db );

  /**
   * @return \qck\Sql\Interfaces\Column
   */
  abstract function toSqlColumn();

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

  function __construct( $Name )
  {
    $this->Name = $Name;
    $this->Unique = $Unique;
    $this->Index = $Index;
  }

  function getName()
  {
    return $this->Name;
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
  protected $Unique=false;
  protected $Index=false;

}
