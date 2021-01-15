<?php

namespace Qck;

/**
 * 
 * @author muellerm
 */
interface Snippet
{

    /**
     * 
     * @return string
     */
    public function toString( $indent = null, $level=0 );
}
