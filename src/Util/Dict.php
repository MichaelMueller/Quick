<?php

namespace Qck\Util;

/**
 *
 * @author muellerm
 */
class Dict implements \Qck\Interfaces\Dict
{

    static function create( array $data = [] )
    {
        return new Dict( $data );
    }

    function __construct( array $data = [] )
    {
        $this->fromArray( $data );
    }

    function toArray()
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

    public function clear()
    {
        $this->data = [];
        return $this;
    }

    public function set( $Key, $Value )
    {
        $this->data[ $Key ] = $Value;
        return $this;
    }

    function remove( $Key )
    {
        unset( $this->data[ $Key ] );
        return $this;
    }

    public function reduceTo( ...$keys )
    {
        foreach ( array_keys( $this->data ) as $key )
            if ( !in_array( $key, $keys ) )
                unset( $this->data[ $key ] );
        return $this;
    }

    public function implodeWithKeys( $glueKeyValue = ": ", $glueRecord = ", " )
    {
        $text    = "";
        $numArgs = count( $this->data );
        $i       = 0;
        foreach ( $this->data as $key => $value )
        {
            ++$i;
            $text .= $key . $glueKeyValue . $value;
            if ( $i < $numArgs )
                $text .= $glueRecord;
        }
        return $text;
    }

    public function fromArray( array $data )
    {
        $this->data = $data;
        return $this;
    }

    public function merge( ... $arrays )
    {
        $this->data = array_merge( $this->data, $arrays );
        return $this;
    }

    /**
     *
     * @var array
     */
    protected $data = [];

    /**
     *
     * @var \Qck\Interfaces\CliParser
     */
    protected $cliParser;

    // STATE

    /**
     *
     * @var bool
     */
    protected $httpRequest;

}
