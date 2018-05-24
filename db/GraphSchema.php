<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class GraphSchema
{

  function addMetaObject( MetaObject $MetaObject )
  {
    $this->MetaObjects[ $MetaObject->getUuid() ] = $MetaObject;
  }

  function getMetaObjects()
  {
    return $this->MetaObjects;
  }

  /**
   *
   * @var array
   */
  protected $MetaObjects = [];

}
