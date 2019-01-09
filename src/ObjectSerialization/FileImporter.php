<?php

namespace Qck\ObjectSerialization;

/**
 * An interface for exporting objects
 * 
 * @author muellerm
 */
class FileImporter implements \Qck\Interfaces\ObjectSerialization\Importer
{

  function import( $ObjectId, $Reload = false )
  {
    // check if it exists
    $Object = $this->ObjectRegistry->getObject( $ObjectId );
    if ( $Object && $Reload == false )
      return $Object;

    // otherwise loadit    
    $File       = $this->DataFileProvider->getFile( $ObjectId, $this->Serializer->getFileExtension() );
    if ( !$File->exists() )
      return null;
    $DataString = $File->readContents();
    $DataArray  = $this->Serializer->unserialize( $DataString );
    if ( !$DataArray )
      return null;
    $ObjectId   = array_pop( $DataArray );
    $Fqcn       = array_pop( $DataArray );

    // get serialization helper and make it an object again
    $SerializationHelper = $this->SerializationHelperProvider->getSerializationHelper( $Fqcn );
    if ( !$Object )
      $Object              = new $Fqcn();
    $SerializationHelper->fromScalarArray( $Object, $DataArray, $this );
    return $Object;
  }

  /**
   *
   * @var \Qck\Interfaces\ObjectSerialization\SerializationHelperProvider
   */
  protected $SerializationHelperProvider;

  /**
   *
   * @var \Qck\Interfaces\ObjectSerialization\ObjectRegistry
   */
  protected $ObjectRegistry;

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
