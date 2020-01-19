<?php

namespace Qck;

/**
 * An interface for exporting objects
 * 
 * @author muellerm
 */
class ObjectFileDeleter implements \Qck\Interfaces\SinkCleaner
{

  function __construct( \Qck\Interfaces\ObjectRegistry $ObjectRegistry,
                        \Qck\Interfaces\FileSerializationHelper $FileSerializationHelper )
  {
    $this->ObjectRegistry   = $ObjectRegistry;
    $this->FileSerializationHelper = $FileSerializationHelper;
  }

  public function delete( \Qck\Interfaces\Serializable $Object )
  {
    // call export on all possesed objects
    foreach ( $Object->getOwnedObjects() as $OwnedObject )
      $this->delete( $OwnedObject );

    // now convert to array, append meta info (fqcn,id) and convert to string
    $ObjectId = $this->ObjectRegistry->getId( $Object );

    // get or create id and create file based on this id to write to
    $File = $this->FileSerializationHelper->getFile( $ObjectId );
    $File->deleteIfExists();
  }

  /**
   *
   * @var \Qck\Interfaces\ObjectRegistry
   */
  protected $ObjectRegistry;

  /**
   *
   * @var \Qck\Interfaces\FileSerializationHelper
   */
  protected $FileSerializationHelper;

}
