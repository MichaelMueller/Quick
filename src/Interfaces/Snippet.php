<?php

namespace Qck\Interfaces;

/**
 * An interface for an object that can be rendered to HTML
 * 
 * @author muellerm
 */
interface Snippet
{

    /**
     * @return string The output text string
     */
    function __toString();
}