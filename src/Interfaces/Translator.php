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

    /**
     * translates a given label (shortcut to a word or text)
     * @return string
     */
    function trl( $label, $ucFirst = false, ... $args );
}
