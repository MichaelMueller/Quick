<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface Translator
{
    /**
     * @return Language
     */
    function language();
    
    /**
     * translates
     * @return string
     */
    function tr( $textDefaultLanguage, $ucFirst = false, ... $args );
}
