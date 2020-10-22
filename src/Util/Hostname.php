<?php

namespace Qck\Util;

/**
 *
 * @author muellerm
 */
class Hostname implements \Qck\Interfaces\Hostname
{

    function __construct( string $val = null )
    {
        $this->val = $val;
    }

    function get()
    {
        if ( is_null( $this->val ) )
            $this->val = gethostname();
        return $this->val;
    }

    /**
     *
     * @var string
     */
    protected $val;

}
