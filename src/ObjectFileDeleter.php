<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class ObjectFileDeleter implements Interfaces\ObjectDatabase
{

  public function deleteMany( $Fqcn, Interfaces\ObjectProperties $Properties )
  {
    // get MetaObject for object
    $MetaObject = $this->MetaObjects->get( $Fqcn );
  }

  public function delete( $Object )
  {
    // get MetaObject for object
    $MetaObject = $this->MetaObjects->get( get_class( $Object ) );

    // get referenced objects
    // save all referenced objects first
    foreach ( $MetaObject->getReferencedObjects( $Object ) as $ReferencedObject )
      $this->delete( $ReferencedObject );
  }

  /**
   *
   * @var Interfaces\ObjectLoader 
   */
  protected $ObjectLoader;

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
