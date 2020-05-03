<?php

namespace Qck;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class LazyLoadedObject implements Interfaces\LazyLoadedObject
{

    function __construct( Interfaces\ObjectSource $ObjectSource, $ObjectId )
    {
        $this->ObjectSource = $ObjectSource;
        $this->ObjectId = $ObjectId;
    }

    function __call( $name, $arguments )
    {
        $this->assertObjectLoaded();
        call_user_func_array( [$this->Object, $name], $arguments );
    }

    function __set( $name, $value )
    {
        $this->assertObjectLoaded();
        $this->Object->$name = $value;
    }

    function __get( $name )
    {
        $this->assertObjectLoaded();
        return $this->Object->$name;
    }

    function __toString()
    {
        $this->assertObjectLoaded();
        return $this->Object->__toString();
    }

    function getObject()
    {
        return $this->Object;
    }

    protected function assertObjectLoaded()
    {
        if (!$this->Loaded)
        {
            $this->Object = $this->ObjectSource->load( $this->ObjectId );
            if ($this->Object instanceof LazyLoadedObject)
                $this->Object = $this->Object->getObject();
            $this->Loaded = true;
        }
    }

    public function getObjectId()
    {
        return $this->ObjectId;
    }

    public function getObjectSource()
    {
        return $this->ObjectSource;
    }

    public function isLoaded()
    {
        return $this->Loaded;
    }

    /**
     *
     * @var Interfaces\ObjectSource
     */
    protected $ObjectSource;

    /**
     *
     * @var mixed
     */
    protected $ObjectId;

    /**
     *
     * @var object
     */
    protected $Object;

    /**
     *
     * @var bool
     */
    protected $Loaded = false;

}
