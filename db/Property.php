<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
abstract class Property implements SchemaElement
{

  abstract function toSqlDatatype( Sql\DatabaseDictionary $Dict );

  function __construct( $Id, $Name )
  {
    $this->Id = $Id;
    $this->Name = $Name;
  }

  function getMetaObject()
  {
    return $this->MetaObject;
  }

  function setMetaObject( MetaObject $MetaObject )
  {
    $this->MetaObject = $MetaObject;
  }

  function getId()
  {
    return $this->Id;
  }

  function getName()
  {
    return $this->Name;
  }

  function getSqlMapper()
  {
    return new PropertySqlMapper( $this );
  }

  public function hasChanged( SchemaElement $Other )
  {
    return get_class( $this ) != get_class( $Other );
  }

  protected $Id;

  /**
   *
   * @var MetaObject 
   */
  protected $MetaObject;
  protected $Name;

}
