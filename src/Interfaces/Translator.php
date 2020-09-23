<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface Translator
{
    /**
     * translates a word!
     * @return string
     */
    function tr( $defaultWord, $ucFirst = false, ... $args );
    
    
    
}
