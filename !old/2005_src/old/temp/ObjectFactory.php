<?php

namespace Qck;

/**
 * An interface for exporting objects
 * 
 * @author muellerm
 */
class ObjectFactory implements \Qck\Interfaces\ObjectFactory
{

    function addFactory($Fqcn, callable $Factory)
    {
        $this->FqcnToFactoryMap[$Fqcn] = $Factory;
    }

    public function create($Fqcn)
    {
        return isset($this->FqcnToFactoryMap[$Fqcn]) ? call_user_func($this->FqcnToFactoryMap[$Fqcn]) : new $Fqcn();
    }

    protected $FqcnToFactoryMap = [];

}
