<?php

namespace Qck\Interfaces;

/**
 * A ContentNegotiator for deciding on the output
 * 
 * @author muellerm
 */
interface ContentNegotiator
{

    /**
     * 
     * @param array $ProvidedLanguages
     * @param string $default
     * @return string
     */
    function getPreferredLanguage( array $ProvidedLanguages, $default = "en" );

    /**
     * 
     * @param array $ProvidedContentTypes
     * @param string $default
     * @return string
     */
    function getPreferredContentType( array $ProvidedContentTypes, $default = HttpContent::CONTENT_TYPE_TEXT_HTML );
}
