<?php

namespace Qck\Interfaces;

/**
 *
 * @author donni
 */
interface LanguageConfig
{

    /**
     * 
     * @param string $langCode, see Language
     * @return bool
     */
    function supports( $langCode );

    /**
     *      
     * @return string
     */
    function defaultLanguage();
}
