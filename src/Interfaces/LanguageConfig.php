<?php

namespace Qck\Interfaces;

/**
 *
 * @author donni
 */
interface LanguageConfig
{    
    /**
     * @return string[] language shortcuts array
     */
    function supportedLanguages();

    /**
     *      
     * @return string
     */
    function defaultLanguage();
}
