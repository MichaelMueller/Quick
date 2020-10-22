<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Language implements \Qck\Interfaces\Language
{

    const COOKIE_EXPIRE = 2147483647;

    function __construct( \Qck\Interfaces\LanguageConfig $languageconfig, \Qck\Interfaces\HttpHeader $httpHeader, $langKey = "lang" )
    {
        $this->languageconfig = $languageconfig;
        $this->httpHeader     = $httpHeader;
        $this->langKey        = $langKey;
    }

    function get()
    {
        if ( $this->lang )
            return $this->lang;

        $this->lang = $this->languageconfig->defaultLanguage();

        // prio: GET/POST -> COOKIE -> BROWSER -> DEFAULT
        $lang          = $this->languageconfig->request()->args()->get( $this->langKey );
        $langSupported = $lang !== null && in_array( $lang, $this->languageconfig->languageConfig()->supportedLanguages() );
        if ( $langSupported )
        {
            $this->lang = $lang;
            if ( $this->httpHeader )
                if ( !isset( $_COOKIE[ $this->langKey ] ) || $_COOKIE[ $this->langKey ] != $lang )
                    $this->httpHeader->addCookie( $this->langKey, $lang, self::COOKIE_EXPIRE );
        }
        else
            $this->lang = $this->getBrowserLanguageOrDefault();
        return $this->lang;
    }

    protected function getBrowserLanguageOrDefault()
    {
        if ( isset( $_SERVER[ 'HTTP_ACCEPT_LANGUAGE' ] ) )
        {
            $langs = explode( ',', $_SERVER[ 'HTTP_ACCEPT_LANGUAGE' ] );
            foreach ( $langs as $lang )
            {
                $browserLang = mb_strtolower( mb_substr( $lang, 0, 2 ) );
                if ( in_array( $browserLang, $this->languageconfig->supportedLanguages() ) )
                    return $browserLang;
            }
        }
        return $this->languageconfig->defaultLanguage();
    }

    /**
     *
     * @var \Qck\Interfaces\LanguageConfig
     */
    protected $languageconfig;

    /**
     *
     * @var \Qck\Interfaces\HttpHeader
     */
    protected $httpHeader;

    /**
     *
     * @var string
     */
    protected $langKey;

    // state

    /**
     *
     * @var string
     */
    protected $lang;

}
