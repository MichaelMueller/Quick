<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface Translator
{

    /**
     * translates
     * @return string
     */
    function tr( $defaultWord, $ucFirst = false, ... $args );
}
