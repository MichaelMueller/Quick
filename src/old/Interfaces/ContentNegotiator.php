<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface ContentNegotiator
{

    /**
     * 
     * @return string
     * @see Output
     */
    function getRequestContentType( $DeliverableContentTypes = [] );

    /**
     * 
     * @return string
     * @see Output
     */
    function getRequestedCharset( $DeliverableCharsets = [] );
    
    /**
     * 
     * @return string
     * @see Output
     */
    function getRequestedLanguage( $DeliverableLanguages = [] );
}
