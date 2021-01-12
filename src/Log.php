<?php

namespace Qck;

class Log
{

    function __construct( RequestFactory $httpRequestFactory )
    {
        $this->httpRequestFactory = $httpRequestFactory;
    }

    function print( $text, $isError = false )
    {
        $date = DateTime::createFromFormat( 'U.u', microtime( TRUE ) );
        $text = sprintf( "%s: %s", $date->format( 'Y-m-d H:i:s.u' ), $text );
        if ( $isError && $this->httpRequestFactory->hasHttpRequest() == false )
            fwrite( STDERR, $text );
        else
            print($text );
    }

    function info( $text, ...$args )
    {
        $this->print( vprintf( $text, $args ) );
    }

    function warn( $text, ...$args )
    {
        $this->print( vprintf( $text, $args ) );
    }

    function error( $text, ...$args )
    {
        $this->print( vprintf( $text, $args ), true );
    }

    /**
     *
     * @var RequestFactory
     */
    protected $httpRequestFactory;

}
