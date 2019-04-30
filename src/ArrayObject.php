<?php

namespace Qck;

/**
 * An interface for exporting objects
 * 
 * @author muellerm
 */
class ArrayObject implements \Qck\Interfaces\ArrayObject
{

    function __construct ( $Data = [], $IsSet = false )
    {
        $this->IsSet = $IsSet;
        $this->Data = $Data;
    }

    public function add ( $Value )
    {
        
    }

    public function get ( $key )
    {
        
    }

    public function getData ()
    {
        
    }

    public function keys ()
    {
        
    }

    public function remove ( $Key, $Strict = true, $Reorder = false )
    {
        
    }

    public function set ( $Key, $Value )
    {
        
    }

    public function setData ( array $Data )
    {
        
    }

    protected $IsSet = false;
    protected $Data = [];

}
