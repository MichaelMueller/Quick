<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class FileObjectSaver implements Interfaces\ObjectSaver
{

  public function save( $Object )
  {
    // get schema for object
    $Fqcn       = get_class( $Object );
    $MetaObject = $this->MetaObjects->get( $Fqcn );

    // get referenced objects
    // save all referenced objects first
    foreach ( $MetaObject->getReferencedObjects( $Object ) as $ReferencedObject )
      $this->save( $ReferencedObject );

    // add referenced object ids (stored internally in property array) to data array
    $StringArray = $MetaObject->toStringArray( $Object );

    // create data file using an existing or new id
    $File = $this->ObjectFileRegistry->getFile( $Object, $this->Serializer->getFileExtension() );

    // use serializer to store data array
    $File->writeContents( $this->Serializer->serialize( $StringArray ) );
  }

  /**
   *
   * @var Interfaces\MetaObjects 
   */
  protected $MetaObjects;

  /**
   *
   * @var Interfaces\ObjectFileRegistry 
   */
  protected $ObjectFileRegistry;

  /**
   *
   * @var Interfaces\Serializer 
   */
  protected $Serializer;

}
