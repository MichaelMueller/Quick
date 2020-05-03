<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Hostname implements Interfaces\Hostname
{

    function __construct( string $Value = null )
    {
        $this->Value = $Value;
    }

    function get()
    {
        if ( is_null( $this->Value ) )
            $this->Value = gethostname();
        return $this->Value;
    }

    /**
     *
     * @var string
     */
    protected $Value;

}
