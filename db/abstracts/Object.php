<?php

namespace qck\db\abstracts;

/**
 *
 * @author muellerm
 */
abstract class Object implements \qck\db\interfaces\Object
{

  public function addObserver( \qck\db\interfaces\ObjectObserver $Observer )
  {
    $this->Observer[] = $Observer;
  }

  protected function notifyObserver()
  {
    /* @var $Observer qck\db\interfaces\ObjectObserver */
    foreach ( $this->Observer as $Observer )
      $Observer->onObjectModified( $this );
  }

  public function getData()
  {
    return $this->Data;
  }

  function getId()
  {
    return $this->Id;
  }

  function setId( $Id )
  {
    $this->Id = $Id;
  }

  public function setData( array $Data )
  {
    $this->Data = $Data;
    $this->notifyObserver();
  }

  private $Data = [];
  private $Id;
  private $Observer;

}
