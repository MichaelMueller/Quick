<?php

namespace Qck\Interfaces;

/**
 * An interface for an object that can be rendered to HTML
 * 
 * @author muellerm
 */
interface NodeVisitor
{
    /**
     * 
     * @param \Qck\Interfaces\Node $Node
     */
    function process(Node $Node);
}
