<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class Database implements interfaces\Database, interfaces\ObjectObserver, interfaces\ObjectListObserver
{

  const OBJ_CREATED = 0;
  const OBJ_MODIFIED = 1;
  const SET_OBJECT_ADDED = 2;
  const SET_OBJECT_REMOVED = 3;
  const DELETE_ISSUED = 4;

  function __construct( \PDO $Pdo, interfaces\Schema $Schema )
  {
    $this->Pdo = $Pdo;
    $this->Schema = $Schema;
    $this->StatementFactory = new StatementFactory();
    $this->Pdo->beginTransaction();
  }
  
  function installSchema()
  {
    
  }

  public function create( $Fqcn )
  {
    $Obj = new $Fqcn();
    if ( $Obj instanceof interfaces\Object )
    {
      $Obj->addObserver( $this );
      $this->NewObjects[] = $Obj;
      if ( $Obj instanceof ObjectList )
        $Obj->addObjectListObserver( $this );
    }
    return $Obj;
  }

  public function commit()
  {
    $this->Pdo->commit();
  }

  public function delete( $Fqcn, interfaces\Expression $Expression )
  {
    $Params = [];
    $delete = $this->StatementFactory->createDelete( $Fqcn, $Expression, $Params );
    $delete->execute( $Params );
  }

  public function onObjectAdded( interfaces\ObjectList $ObjectList, interfaces\Object $Object )
  {
    // todo
  }

  public function onObjectModified( interfaces\Object $Object )
  {
    if ( $this->DiscardNextModificationEvent )
    {
      $this->DiscardNextModificationEvent = false;
      return;
    }
    $index = array_search( $Object, $this->NewObjects, true );
    if ( $index !== false )
    {
      unset( $this->NewObjects[ $index ] );
      $this->insert( $Object );
    }
    else
      $this->update( $Object );
  }

  public function onObjectRemoved( interfaces\ObjectList $ObjectList, interfaces\Object $Object )
  {
    // todo
  }

  public function select( $Fqcn, interfaces\Expression $Expression, $offset = null,
                          $limit = null )
  {
    // todo
  }

  protected function insert( interfaces\Object $Object )
  {
    $IdColumn = null;
    $Params = [];
    $insert = $this->StatementFactory->createInsert( $Object, $Params, $IdColumn );
    $insert->execute( $Params );
    $this->DiscardNextModificationEvent = true;
    $Object->set( $IdColumn, $this->Pdo->lastInsertId() );
  }

  protected function update( interfaces\Object $Object )
  {
    $Params = [];
    $update = $this->StatementFactory->createUpdate( $Object, $Params );
    $update->execute( $Params );
  }

  public function selectById( $Fqcn, $IdValue )
  {
    // todo
  }

  /**
   *
   * @var \PDO
   */
  protected $Pdo;

  /**
   *
   * @var interfaces\Schema
   */
  protected $Schema;

  /**
   *
   * @var StatementFactory
   */
  protected $StatementFactory;

  /**
   *
   * @var array
   */
  protected $NewObjects = [];

  /**
   *
   * @var bool 
   */
  protected $DiscardNextModificationEvent = false;

}
