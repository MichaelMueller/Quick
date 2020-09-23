<?php

namespace Qck\Interfaces;

/**
 * Encapsulation of everything that is known when a request is sent to this system (input
 * variables, env, client and config infos)
 * @author muellerm
 */
interface Arguments extends ImmutableDict, HttpRequestDetector
{

    /**
     * @return Arguments
     */
    function checkNotNull( $field, $error=null );

    /**
     * @return Arguments
     */
    function checkMinLength( $field, $numChars, $error=null );

    /**
     * @return Arguments
     */
    function checkEmail( $field, $error=null );

    /**
     * @return string[]
     */
    function validate();
}
