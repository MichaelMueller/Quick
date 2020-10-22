<?php

namespace Qck;

/**
 *
 * @author mueller
 */
class Translator implements Interfaces\Translator
{

    function __construct( Interfaces\App $app, Storage $storage, $throwExceptionOnMissingTr = false, $cutLength = 16 )
    {
        $this->app                       = $app;
        $this->storage                   = $storage;
        $this->throwExceptionOnMissingTr = $throwExceptionOnMissingTr;
        $this->cutLength                 = $cutLength;
    }

    function setThrowExceptionOnMissingTr( $throwExceptionOnMissingTr )
    {
        $this->throwExceptionOnMissingTr = $throwExceptionOnMissingTr;
    }

    function tr( $defaultWord, $ucFirst = false, ... $args )
    {
        $lang = $this->app->language()->get();

        if ($lang == $this->app->languageConfig()->defaultLanguage())
            $tr = $defaultWord;
        else
        {
            if (!isset( $this->trs[$lang][$defaultWord] ))
            {
                if ($this->throwExceptionOnMissingTr)
                    throw new \Exception( "Missing translation for \"" . $defaultWord . "\"", \Qck\Interfaces\HttpHeader::EXIT_CODE_INTERNAL_ERROR );
                else
                    $tr = "no-tr:\"" . (strlen( $defaultWord ) > $this->cutLength ? substr( $defaultWord, 0, $this->cutLength ) . "..." : $defaultWord) . "\"";
            }
            else
                $tr = $this->trs[$lang][$defaultWord];
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

    /**
     *
     * @var int
     */
    protected $cutLength;

}
