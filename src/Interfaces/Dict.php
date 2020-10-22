<?php

namespace Qck\Interfaces;

/**
 * Service class for sending messages to an admin
 * @author muellerm
 */
interface Dict extends ImmutableDict
{
    /**
     * 
     * @param array $data
     * @return Dict
     */
    function fromArray(array $data);
    
    /**
     * 
     * @param array $data
     * @return Dict
     */
    function merge(...$arrays);
    
    /**
     * 
     * @param mixed $Key
     * @param mixed $Value
     * @return Dict
     */
    function set( $Key, $Value );
    
    /**
     * 
     * @param string $Key
     * @return Dict
     */
    function remove($Key);
    
    /**
     * 
     * @param string[] $keys
     * @return Dict
     */
    function reduceTo(...$keys);
    
    /**
     * @return Dict
     */
    function clear();
}
