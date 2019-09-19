<?php

namespace Qck\Interfaces;

/**
 * Abstraction for a Source of arbitrary Objects based on Ids
 * 
 * @author muellerm
 */
interface LazyLoadedObject
{

    /**
     * @return mixed int or string
     */
    function getObjectId();
    
    /**
     * @return ObjectSource
     */
    function getObjectSource();

    /**
     * @return mixed int or string
     */
    function getObject();

    /**
     * @return mixed int or string
     */
    function isLoaded();
}
