<?php

namespace Qck\Interfaces;

/**
 * An interface for an object that can be rendered to HTML
 * 
 * @author muellerm
 */
interface Node
{
    /**
     * 
     * @param \Qck\Interfaces\NodeVisitor $NodeVisitor
     * @param array $VisitedNodes
     */
    function accept( NodeVisitor $NodeVisitor, array &$VisitedNodes = [] );
}
