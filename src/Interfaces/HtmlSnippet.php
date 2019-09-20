<?php

namespace Qck\Interfaces;

/**
 * An interface for an object that can be rendered to a HTML snippet (which may not be a complete and valid html page)
 * 
 * @author muellerm
 */
interface HtmlSnippet
{

    /**
     * @return string Html String
     */
    function renderHtml();
}
