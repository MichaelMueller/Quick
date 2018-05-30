<?php

namespace qck\Data2;

/**
 *
 * @author muellerm
 */
class LazyLoader implements Interfaces\UnloadedObject
{

  function __construct( $Fqcn, $Id, Interfaces\Db $Db = null )
  {
    $this->Fqcn = $Fqcn;
    $this->Id = $Id;
    $this->Db = $Db;
  }

  function setDb( Db $Db )
  {
    $this->Db = $Db;
  }

  function getId()
  {
    return $this->Id;
  }

  public function load()
  {
    return $this->Db->load( $this->Fqcn, $this->Id );
  }

  public function getFqcn()
  {
    return $this->Fqcn;
  }

  /**
   *
   * @var string 
   */
  protected $Fqcn;

  /**
   *
   * @var int 
   */
  protected $Id;

  /**
   *
   * @var Interfaces\Db
   */
  protected $Db;

}
