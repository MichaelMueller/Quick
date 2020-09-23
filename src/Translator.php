<?php

namespace Qck;

/**
 * Description of Web
 *
 * @author mueller
 */
abstract class Translator implements Interfaces\Translator
{

    /**
     * void
     */
    abstract function createTrs();

    function __construct( Interfaces\LanguageConfig $languageConfig, Interfaces\Language $language )
    {
        $this->languageConfig = $languageConfig;
        $this->language       = $language;
    }

    function setThrowExceptionOnMissingTr( $throwExceptionOnMissingTr )
    {
        $this->throwExceptionOnMissingTr = $throwExceptionOnMissingTr;
    }

    function tr( $defaultWord, $ucFirst = false, ... $args )
    {
        if ( is_null( $this->trs ) )
        {
            $this->trs          = [];
            foreach ( $this->supportedLanguages() as $lang )
                $this->trs[ $lang ] = [];
            $this->createTrs();
        }
        $lang = $this->language->get();

        if ( $lang == $this->languageConfig->defaultLanguage() )
            $tr = $defaultWord;
        else
        {
            if ( !isset( $this->trs[ $lang ][ $defaultWord ] ) )
            {
                if ( $this->throwExceptionOnMissingTr )
                    throw new \Exception( "Missing translation for label " . $defaultWord, \Qck\Interfaces\HttpHeader::EXIT_CODE_INTERNAL_ERROR );
                else
                    $tr = "no-tr-word:\"" . (strlen( $defaultWord ) > 48 ? substr( $defaultWord, 0, 48 ) . "..." : $defaultWord) . "\"";
            }
            else
                $tr = $this->trs[ $lang ][ $defaultWord ];
        }
        return $this->convertString( $tr, $ucFirst, $args );
    }

    protected function convertString( $string, $ucFirst = false, array $args = [] )
    {
        $string = count( $args ) > 0 ? vsprintf( $string, $args ) : $string;
        return $ucFirst ? ucfirst( $string ) : $string;
    }

    /**
     *
     * @var Interfaces\LanguageConfig
     */
    protected $languageConfig;

    /**
     *
     * @var Interfaces\Language
     */
    protected $language;

    /**
     *
     * @var bool
     */
    protected $throwExceptionOnMissingTr = false;

    /**
     *
     * @var string[] 
     */
    protected $trs;

}
