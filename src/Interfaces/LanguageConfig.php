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
     * @param string $Lang, see Language
     * @return bool
     */
    function isSupported( $Lang );

    /**
     *      
     * @return string
     */
    function getDefaultLanguage();
}
