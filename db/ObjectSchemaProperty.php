<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class MetaObjectProperty extends Property
{

  public function __construct( $Id, $Name, MetaObject $MetaObject )
  {
    parent::__construct( $Id, $Name );
    $this->MetaObject = $MetaObject;
  }

  /**
   * @var MetaObject 
   */
  protected $MetaObject;

}
