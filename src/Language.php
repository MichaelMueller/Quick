<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Language implements \Qck\Interfaces\Language
{

    const COOKIE_EXPIRE = 2147483647;

    function __construct( \Qck\Interfaces\App $app, string $langKey = "lang" )
    {
        $this->app     = $app;
        $this->langKey = $langKey;
    }

    function get()
    {
        if ( $this->lang )
            return $this->lang;

        $this->lang = $this->app->languageConfig()->defaultLanguage();

        // prio: GET/POST -> COOKIE -> BROWSER -> DEFAULT
        $lang          = $this->app->request()->args()->get( $this->langKey );
        $langSupported = $lang !== null && in_array( $lang, $this->app->languageConfig()->supportedLanguages() );
        if ( $langSupported )
        {
            $this->lang = $lang;
            if ( $this->app->httpHeader() )
                if ( !isset( $_COOKIE[ $this->langKey ] ) || $_COOKIE[ $this->langKey ] != $lang )
                    $$this->app->httpHeader()->addCookie( $this->langKey, $lang, self::COOKIE_EXPIRE );
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
                if ( in_array( $browserLang, $this->app->languageConfig()->supportedLanguages() ) )
                    return $browserLang;
            }
        }
        return $this->app->languageConfig()->defaultLanguage();
    }

    /**
     *
     * @var \Qck\Interfaces\App
     */
    protected $app;

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
