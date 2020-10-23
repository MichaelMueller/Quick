<?php

namespace Qck;

/**
 *
 * @author mueller
 */
class Translator implements Interfaces\Translator
{

    function tr( $textDefaultLanguage, $ucFirst = false, ... $args )
    {
        $id     = sprintf( "%u", crc32( $textDefaultLanguage ) );
        $filter = function( $record ) use( $id )
        {
            return $record[ "id" ] == $id;
        };
        $translation = $this->storage->records( $filter );
        if ( !$translation )
        {
            $record                                             = array_fill_keys( $this->languageConfig->supportedLanguages(), null );
            $record[ $this->languageConfig->defaultLanguage() ] = $textDefaultLanguage;
            $this->storage->write( $record, $id );
        }
        else
            $record = $translations[ $id ];
        // got a record here
        $lang   = $this->language->get();
        if ( !isset( $translations[ $id ][ $lang ] ) )
        {
            if ( $this->throwExceptionOnMissingTr )
                throw new \Exception( "Missing translation for \"" . $textDefaultLanguage . "\"", \Qck\Interfaces\HttpHeader::EXIT_CODE_INTERNAL_ERROR );
            else
                $tr = "no-tr:\"" . (strlen( $textDefaultLanguage ) > $this->cutLength ? substr( $textDefaultLanguage, 0, $this->cutLength ) . "..." : $textDefaultLanguage) . "\"";
        }
        else
            $tr = $translations[ $id ][ $lang ];

        return $this->convertString( $tr, $ucFirst, $args );
    }

    protected function convertString( $string, $ucFirst = false, array $args = [] )
    {
        $string = count( $args ) > 0 ? vsprintf( $string, $args ) : $string;
        return $ucFirst ? ucfirst( $string ) : $string;
    }

    public function language(): Interfaces\Language
    {
        return $this->language();
    }

    /**
     *
     * @var Interfaces\Language
     */
    protected $language;

    /**
     *
     * @var Interfaces\LanguageConfig
     */
    protected $languageConfig;

    /**
     *
     * @var Interfaces\Storage
     */
    protected $storage;

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
