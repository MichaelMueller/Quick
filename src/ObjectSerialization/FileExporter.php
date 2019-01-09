<?php

namespace Qck\ObjectSerialization;

/**
 * An interface for exporting objects
 * 
 * @author muellerm
 */
class FileExporter implements \Qck\Interfaces\ObjectSerialization\Exporter
{

  function __construct( \Qck\Interfaces\ObjectSerialization\SerializationHelperProvider $SerializationHelperProvider,
                        \Qck\Interfaces\ObjectSerialization\IdProvider $IdProvider,
                        \Qck\Interfaces\Serializer $Serializer,
                        \Qck\Interfaces\ObjectSerialization\DataFileProvider $DataFileProvider )
  {
    $this->SerializationHelperProvider = $SerializationHelperProvider;
    $this->IdProvider                  = $IdProvider;
    $this->Serializer                  = $Serializer;
    $this->DataFileProvider            = $DataFileProvider;
  }

  function export( $Object )
  {
    // get serialization helper
    $Fqcn                = get_class( $Object );
    $SerializationHelper = $this->SerializationHelperProvider->getSerializationHelper( $Fqcn );

    // call export on all possesed objects
    foreach ( $SerializationHelper->getOwnedObjects( $Object ) as $OwnedObject )
      $this->export( $OwnedObject );

    // now convert to array, append meta info (fqcn,id) and convert to string
    $DataArray   = $SerializationHelper->toScalarArray( $Object, $this->IdProvider );
    $ObjectId    = $this->IdProvider->getObjectId( $Object );
    $DataArray[] = $Fqcn;
    $DataArray[] = $ObjectId;
    $DataString  = $this->Serializer->serialize( $DataArray );

    // get or create id and create file based on this id to write to
    $File = $this->DataFileProvider->getFile( $ObjectId, $this->Serializer->getFileExtension() );
    $File->writeContents( $DataString );
  }

  /**
   *
   * @var \Qck\Interfaces\ObjectSerialization\SerializationHelperProvider
   */
  protected $SerializationHelperProvider;

  /**
   *
   * @var \Qck\Interfaces\ObjectSerialization\IdProvider
   */
  protected $IdProvider;

  /**
   *
   * @var \Qck\Interfaces\Serializer
   */
  protected $Serializer;

  /**
   *
   * @var \Qck\Interfaces\ObjectSerialization\DataFileProvider
   */
  protected $DataFileProvider;

}
