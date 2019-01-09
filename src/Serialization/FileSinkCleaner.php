<?php

namespace Qck\Serialization;

/**
 * An interface for exporting objects
 * 
 * @author muellerm
 */
class FileSinkCleaner implements \Qck\Interfaces\Serialization\SinkCleaner
{

  function __construct( \Qck\Interfaces\Serialization\ObjectRegistry $ObjectRegistry,
                        \Qck\Interfaces\Serialization\DataFileProvider $DataFileProvider )
  {
    $this->ObjectRegistry   = $ObjectRegistry;
    $this->DataFileProvider = $DataFileProvider;
  }

  public function delete( \Qck\Interfaces\Serialization\Serializable $Object )
  {
    // call export on all possesed objects
    foreach ( $Object->getOwnedObjects() as $OwnedObject )
      $this->delete( $OwnedObject );

    // now convert to array, append meta info (fqcn,id) and convert to string
    $ObjectId = $this->ObjectRegistry->getId( $Object );

    // get or create id and create file based on this id to write to
    $File = $this->DataFileProvider->getFile( $ObjectId );
    $File->deleteIfExists();
  }

  /**
   *
   * @var \Qck\Interfaces\Serialization\ObjectRegistry
   */
  protected $ObjectRegistry;

  /**
   *
   * @var \Qck\Interfaces\Serialization\DataFileProvider
   */
  protected $DataFileProvider;

}
