<?php

namespace Qck\Serialization;

/**
 * An interface for exporting objects
 * 
 * @author muellerm
 */
class FileSink implements \Qck\Interfaces\Serialization\Sink
{

  function __construct(
  \Qck\Interfaces\Serialization\ObjectIdProvider $ObjectIdProvider,
  \Qck\Interfaces\Serialization\DataFileProvider $DataFileProvider )
  {
    $this->ObjectIdProvider = $ObjectIdProvider;
    $this->DataFileProvider = $DataFileProvider;
  }

  function save( \Qck\Interfaces\Serialization\Serializable $Object )
  {
    // call export on all possesed objects
    foreach ( $Object->getOwnedObjects() as $OwnedObject )
      $this->save( $OwnedObject );

    // now convert to array, append meta info (fqcn,id) and convert to string
    $DataArray   = $Object->toScalarArray( $this->ObjectIdProvider );
    $ObjectId    = $this->ObjectIdProvider->getId( $Object );
    $DataArray[] = get_class( $Object );
    $DataString  = $this->DataFileProvider->getArraySerializer()->serialize( $DataArray );

    // get or create id and create file based on this id to write to
    $File = $this->DataFileProvider->getFile( $ObjectId );
    $File->writeContents( $DataString );
  }

  /**
   *
   * @var \Qck\Interfaces\Serialization\ObjectIdProvider
   */
  protected $ObjectIdProvider;

  /**
   *
   * @var \Qck\Interfaces\Serialization\DataFileProvider
   */
  protected $DataFileProvider;

}
