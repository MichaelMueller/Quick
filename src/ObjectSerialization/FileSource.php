<?php

namespace Qck\Serialization;

/**
 * An interface for exporting objects
 * 
 * @author muellerm
 */
class FileSource implements \Qck\Interfaces\Serialization\Source
{

  function load( $ObjectId, $Reload = false )
  {
    // check if it exists
    $Object = $this->ObjectRegistry->getObject( $ObjectId );
    if ( $Object && $Reload == false )
      return $Object;

    // otherwise load it    
    $File       = $this->DataFileProvider->getFile( $ObjectId );
    if ( ! $File->exists() )
      return null;
    $DataString = $File->readContents();
    $DataArray  = $this->DataFileProvider->getSerializer()->unserialize( $DataString );
    if ( ! $DataArray )
      return null;
    $Fqcn       = array_pop( $DataArray );

    // if object does not exist, create it and save in registry
    if ( ! $Object )
    {
      $Object = new $Fqcn();
      $this->ObjectRegistry->setObject( $ObjectId, $Object );
    }
    // load data
    $Object->fromScalarArray( $DataArray, $this );
    return $Object;
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
