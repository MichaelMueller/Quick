<?php

namespace Qck;

/**
 * An interface for exporting objects
 * 
 * @author muellerm
 */
class ObjectToFileExporter
{

  function __construct( \Qck\Interfaces\ObjectArrayMappers $ObjectArrayMappers,
                        \Qck\Interfaces\ReferentialInfos $ReferentialInfos,
                        \Qck\Interfaces\ObjectIdProvider $ObjectIdProvider,
                        \Qck\Interfaces\ArraySerializer $ArraySerializer,
                        \Qck\Interfaces\DataDirectory $DataDirectory )
  {
    $this->ObjectArrayMappers = $ObjectArrayMappers;
    $this->ReferentialInfos   = $ReferentialInfos;
    $this->ObjectIdProvider   = $ObjectIdProvider;
    $this->ArraySerializer    = $ArraySerializer;
    $this->DataDirectory      = $DataDirectory;
  }

  public function save( $Object )
  {
    $Fqcn             = get_class( $Object );
    $ReferentialInfos = $this->ReferentialInfos->getReferentialInfo( $Fqcn );
    // call save on all possesed objects
    foreach ( $ReferentialInfos->getOwnedObjects( $Object ) as $OwnedObject )
      $this->save( $OwnedObject );

    // now convert to array, append meta info (fqcn,id) and convert to string
    $ObjectArrayMapper = $this->ObjectArrayMappers->getObjectArrayMapper( $Fqcn );
    $DataArray         = $ObjectArrayMapper->toArray( $Object, $this->ObjectIdProvider );
    $ObjectId          = $this->ObjectIdProvider->getId( $Object );
    $DataArray[]       = $Fqcn;
    $DataString        = $this->ArraySerializer->serialize( $DataArray );

    // get or create id and create file based on this id to write to
    $File = $this->DataDirectory->getFile( $ObjectId );
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
   * @var \Qck\Interfaces\ArraySerializer
   */
  protected $ArraySerializer;

  /**
   *
   * @var \Qck\Interfaces\DataDirectory
   */
  protected $DataDirectory;

}
