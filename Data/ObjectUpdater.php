<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class ObjectUpdater implements Interfaces\ObjectObserver
{

  function __construct( ObjectSchema $ObjectSchema, \qck\Sql\Interfaces\Db $SqlDb )
  {
    $this->ObjectSchema = $ObjectSchema;
    $this->SqlDb = $SqlDb;
  }

  public function onModified( Interfaces\Object $Object )
  {
    $Schema = $this->ObjectSchema;
    $Exp = new \qck\Expressions\UuidEquals( $Object->getUuid(), $Schema->getUuidPropertyName() );
    $Data = $Object->getData();

    if ( !$this->SqlDb->isInTransaction() )
      $this->SqlDb->beginTransaction();
    $this->SqlDb->update( $Schema->getSqlTableName(), $Schema->getPropertyNames( false ), $Schema->prepare( $Data, $Object->getModifiedTime() ), $Exp );
    $this->UncomittedChanges = true;
  }

  function hasUncomittedChanges()
  {
    return $this->UncomittedChanges;
  }

  function setChangesCommited()
  {
    $this->UncomittedChanges = false;
  }

  /**
   *
   * @var ObjectSchema
   */
  protected $ObjectSchema;

  /**
   *
   * @var \qck\Sql\Interfaces\Db
   */
  protected $SqlDb;
  protected $UncomittedChanges = true;

}
