<?php

namespace Qck;

/**
 * An interface for exporting objects
 * 
 * @author muellerm
 */
class ObjectFromFileImporter implements \Qck\Interfaces\ObjectSource
{

  function __construct( \Qck\Interfaces\ObjectArrayMappers $ObjectArrayMapperProvider,
                        \Qck\Interfaces\DataDirectory $DataDirectory,
                        \Qck\Interfaces\ObjectArrayMappers $ObjectArrayMapperProvider )
  {
    $this->ObjectArrayMapperProvider = $ObjectArrayMapperProvider;
    $this->DataDirectory             = $DataDirectory;
    $this->ObjectArrayMapperProvider = $ObjectArrayMapperProvider;
  }

  function load( $ObjectId, $Reload = false )
  {
    // check if it exists
    $Object = $this->ObjectRegistry->getObject( $ObjectId );
    if ( $Object && $Reload == false )
      return $Object;

    // otherwise load it    
    $File        = $this->DataDirectory->getFile( $ObjectId );
    if ( !$File->exists() )
      return null;
    $DataString  = $File->readContents();
    $ScalarArray = $this->ArraySerializer->unserialize( $DataString );
    if ( !$ScalarArray )
      return null;
    $Fqcn        = array_pop( $ScalarArray );

    // if object does not exist, create it and save in registry
    if ( !$Object )
    {
      $Object = new $Fqcn();
      $this->ObjectRegistry->setObject( $ObjectId, $Object );
    }
    // load data
    $ObjectArrayMapper = $this->ObjectArrayMapperProvider->getObjectArrayMapper( $Fqcn );
    $ObjectArrayMapper->fromArray( $Object, $ScalarArray, $this, $Reload );

    return $Object;
  }

  public function createLazyLoader( $Id, $Reload = false )
  {
    return function() use($Id, $Reload)
    {
      return $this->load( $Id, $Reload );
    };
  }

  /**
   *
   * @var \Qck\Interfaces\DataDirectory
   */
  protected $DataDirectory;

  /**
   *
   * @var \Qck\Interfaces\ArraySerializer
   */
  protected $ArraySerializer;

  /**
   *
   * @var \Qck\Interfaces\ObjectArrayMapperProvider
   */
  protected $ObjectArrayMapperProvider;

}
