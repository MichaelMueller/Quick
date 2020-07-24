<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Dict implements Interfaces\Dict
{

    function __construct( array $data = [] )
    {
        $this->data = $data;
    }

    function set( $Key, $Value )
    {
        $this->data[ $Key ] = $Value;
    }

    function getdata()
    {
        return $this->data;
    }

    public function keys()
    {
        return array_keys( $this->data );
    }

    public function get( $Name, $Default = null )
    {
        return isset( $this->data[ $Name ] ) ? $this->data[ $Name ] : $Default;
    }

    public function has( $Name )
    {
        return isset( $this->data[ $Name ] );
    }

    /**
     *
     * @var array
     */
    protected $data = [];

}
