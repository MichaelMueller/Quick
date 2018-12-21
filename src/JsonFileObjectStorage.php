<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Qck;

/**
 * Description of JsonFileObjectStorage
 *
 * @author muellerm
 */
class JsonFileObjectStorage implements Interfaces\ObjectStorage
{

  function __construct( Interfaces\File $File )
  {
    $this->File                 = $File;
    $this->DataObject           = new \stdClass();
    $this->DataObject->MetaData = new \stdClass();
  }

  public function set( $Key, $Value )
  {
    $this->DataObject->$Key = $Value;
  }

  public function setPersistableObject( $Key,
                                        Interfaces\PersistableObject $PersistableObject,
                                        $WeakReference = false )
  {
    $this->JsonObject->$Key = $PersistableObject->getId();
    $this->JsonObject;
  }

  /**
   *
   * @var Interfaces\File
   */
  protected $File;

  /**
   *
   * @var \stdClass
   */
  protected $Data;

}
