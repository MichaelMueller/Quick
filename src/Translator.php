<?php

namespace Qck;

/**
 * Description of Web
 *
 * @author mueller
 */
class Translator implements Interfaces\Translator
{

    function __construct( Interfaces\App $app, array $translations, $throwExceptionOnMissingTr = false )
    {
        $this->app                       = $app;
        $this->translations              = $translations;
        $this->throwExceptionOnMissingTr = $throwExceptionOnMissingTr;
    }

    function setThrowExceptionOnMissingTr( $throwExceptionOnMissingTr )
    {
        $this->throwExceptionOnMissingTr = $throwExceptionOnMissingTr;
    }

    function tr( $defaultWord, $ucFirst = false, ... $args )
    {
        $lang = $this->app->language()->get();

        if ( $lang == $this->app->languageConfig()->defaultLanguage() )
            $tr = $defaultWord;
        else
        {
            if ( !isset( $this->trs[ $lang ][ $defaultWord ] ) )
            {
                if ( $this->throwExceptionOnMissingTr )
                    throw new \Exception( "Missing translation for \"" . $defaultWord . "\"", \Qck\Interfaces\HttpHeader::EXIT_CODE_INTERNAL_ERROR );
                else
                    $tr = "no-tr:\"" . (strlen( $defaultWord ) > 48 ? substr( $defaultWord, 0, 48 ) . "..." : $defaultWord) . "\"";
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
     * @var Interfaces\App
     */
    protected $app;

    /**
     *
     * @var array[string[string]] 
     */
    protected $translations;

    /**
     *
     * @var bool
     */
    protected $throwExceptionOnMissingTr;

}
