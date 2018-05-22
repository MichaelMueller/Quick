<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class MetaObject implements SchemaElement
{

  function __construct( $Id, $Fqcn )
  {
    $this->Id = $Id;
    $this->Fqcn = $Fqcn;
    $this->addProperty( new IdProperty() );
  }

  function getId()
  {
    return $this->Id;
  }

  function addProperty( Property $Property )
  {
    $Property->setMetaObject( $this );
    $this->Properties[ $Property->getId() ] = $Property;
  }

  function getProperties()
  {
    return $this->Properties;
  }

  function getName()
  {
    return str_replace( "\\", "_", $this->Fqcn );
  }

  function getFqcn()
  {
    return $this->Fqcn;
  }

  function hasChanged( SchemaElement $Other )
  {
    return get_class( $this ) != get_class( $Other );
  }

  public function getSqlMapper()
  {
    return new MetaObjectSqlMapper( $this );
  }

  protected $Id;
  protected $Fqcn;
  protected $Properties = [];

}
