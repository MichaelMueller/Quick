<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Language implements \Qck\Interfaces\Language
{

    const COOKIE_EXPIRE = 2147483647;

    function __construct( Interfaces\LanguageConfig $LanguageConfig, Interfaces\ImmutableDict $Args, string $LangKey = "lang", Interfaces\HttpHeader $HttpHeader = null )
    {
        $this->LanguageConfig = $LanguageConfig;
        $this->Args           = $Args;
        $this->LangKey        = $LangKey;
        $this->HttpHeader     = $HttpHeader;
    }

    function get()
    {
        static $CurrentLang = null;
        if ( $CurrentLang )
            return $CurrentLang;

        $CurrentLang = $this->LanguageConfig->getDefaultLanguage();

        // prio: GET/POST -> COOKIE -> BROWSER -> DEFAULT
        $SelectedLang          = $this->Args->get( $this->LangKey );
        $SelectedLangSupported = $SelectedLang !== null && $this->LanguageConfig->isSupported( $SelectedLang );
        if ( $SelectedLangSupported )
        {
            $CurrentLang = $SelectedLang;
            if ( $this->HttpHeader )
                if ( !isset( $_COOKIE[ $this->LangKey ] ) || $_COOKIE[ $this->LangKey ] != $SelectedLang )
                    $this->HttpHeader->addCookie( $this->LangKey, $SelectedLang, self::COOKIE_EXPIRE );
        }
        else
            $CurrentLang = $this->getBrowserLanguageOrDefault();
    }

    protected function getBrowserLanguageOrDefault()
    {
        if ( isset( $_SERVER[ 'HTTP_ACCEPT_LANGUAGE' ] ) )
        {
            $langs = explode( ',', $_SERVER[ 'HTTP_ACCEPT_LANGUAGE' ] );
            foreach ( $langs as $lang )
            {
                $BrowserLang = mb_strtolower( mb_substr( $lang, 0, 2 ) );
                if ( $this->LanguageConfig->isSupported( $BrowserLang ) )
                    return $BrowserLang;
            }
        }
        return $this->LanguageConfig->getDefaultLanguage();
    }

    /**
     *
     * @var Interfaces\LanguageConfig
     */
    protected $LanguageConfig;

    /**
     *
     * @var Interfaces\ImmutableDict
     */
    protected $Args;

    /**
     *
     * @var string
     */
    protected $LangKey;

    /**
     *
     * @var Interfaces\HttpHeader
     */
    protected $HttpHeader;

}