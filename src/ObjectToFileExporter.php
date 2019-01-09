<?php

namespace Qck;

/**
 * An interface for exporting objects
 * 
 * @author muellerm
 */
class ObjectToFileExporter
{

  public function save( $Object )
  {
    $Fqcn             = get_class( $Object );
    $ReferentialInfos = $this->ReferentialInfos->getReferentialInfo( $Fqcn );
    // call save on all possesed objects
    foreach ( $ReferentialInfos->getOwnedObjects( $Object ) as $OwnedObject )
      $this->save( $OwnedObject );

    // now convert to array, append meta info (fqcn,id) and convert to string
    $ObjectArrayMapper = $this->ObjectArrayMappers->getObjectArrayMapper( get_class( $Object ) );
    $DataArray         = $ObjectArrayMapper->toArray( $Object, $this->ObjectIdProvider );
    $ObjectId          = $this->ObjectIdProvider->getId( $Object );
    $DataArray[]       = get_class( $Object );
    $DataString        = $this->FileSerializationHelper->getArraySerializer()->serialize( $DataArray );

    // get or create id and create file based on this id to write to
    $File = $this->FileSerializationHelper->getFile( $ObjectId );
    $File->writeContents( $DataString );
  }

  /**
   *
   * @var \Qck\Interfaces\ObjectArrayMappers
   */
  protected $ObjectArrayMappers;

  /**
   *
   * @var \Qck\Interfaces\ReferentialInfos
   */
  protected $ReferentialInfos;

  /**
   *
   * @var \Qck\Interfaces\ObjectIdProvider
   */
  protected $ObjectIdProvider;

  /**
   *
   * @var \Qck\Interfaces\FileSerializationHelper
   */
  protected $FileSerializationHelper;

}
