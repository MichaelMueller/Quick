<?php

namespace Qck;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class FileObjectStorage implements \Qck\Interfaces\ObjectStorage
{

    public function save( $Object )
    {
        $ArrayMapper = $this->ObjectArrayMapperProvider->getObjectArrayMapper( get_class( $Object ) );
        $ScalarArray = $ArrayMapper->toScalarArray( $Object, $this->ObjectIdProvider );
        
    }

    /**
     *
     * @var Interfaces\ObjectArrayMapperProvider 
     */
    protected $ObjectArrayMapperProvider;

    /**
     *
     * @var Interfaces\ObjectIdProvider 
     */
    protected $ObjectIdProvider;

}
