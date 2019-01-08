<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class ObjectFileSaver implements Interfaces\ObjectSaver
{

  public function save( $Object )
  {
    // get ObjectSerializer for object
    $ObjectSerializer = $this->ObjectSerializers->get( get_class( $Object ) );

    // get referenced objects
    // save all referenced objects first
    foreach ( $ObjectSerializer->getReferencedObjects( $Object ) as $ReferencedObject )
      $this->save( $ReferencedObject );

    // add referenced object ids (stored internally in property array) to data array
    $StringArray = $ObjectSerializer->toStringArray( $Object );

    // create data file using an existing or new id
    $File = $this->ObjectFileRegistry->getFile( $Object, $this->Serializer->getFileExtension() );

    // use serializer to store data array
    $File->writeContents( $this->Serializer->serialize( $StringArray ) );
  }

  /**
   *
   * @var Interfaces\ObjectSerializers 
   */
  protected $ObjectSerializers;

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
