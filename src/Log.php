<?php

namespace Qck;

/**
 * Basic logging class.
 * 
 * @todo Implement channels via request / file logging
 * @author Michael Mueller <michaelmuelleronline@gmx.de>
 */
class Log
{

    static function new( Request $request ): Log
    {
        return new Log( $request );
    }

    function __construct( Request $request )
    {
        $this->request = $request;
    }

    function send( LogMessage $logMessage )
    {
        $matchingTopics = array_values( array_intersect( $this->acitveTopics, $logMessage->topics() ) );
        if ( count( $matchingTopics ) == 0 )
            return;
        $text           = strval( $logMessage );
        $this->handleMessage( $text, $matchingTopics );
    }

    protected function handleMessage( $text, $matchingTopics )
    {
        if ( in_array( LogMessage::ERROR, $matchingTopics ) && $this->request->isHttpRequest() == false )
            fwrite( STDERR, $text . PHP_EOL );
        else
            print($text ) . PHP_EOL;
        flush();
    }

    function msg( $text, $topic ): LogMessage
    {
        $logMessage = LogMessage::new( $this, $text, $topic );
        $logMessage->setShowDateTime( $this->showDateTime )->setShowFile( $this->showFile )->setShowTopics( $this->showTopics );
        return $logMessage;
    }

    function addTopic( $activeTopic ): Log
    {
        $this->acitveTopics[] = $activeTopic;
        return $this;
    }

    function info( $text ): LogMessage
    {
        return $this->msg( $text, LogMessage::INFO );
    }

    function warn( $text )
    {
        return $this->msg( $text, LogMessage::WARN );
    }

    function error( $text )
    {
        return $this->msg( $text, LogMessage::ERROR );
    }

    function setShowDateTime( bool $showDateTime ): Log
    {
        $this->showDateTime = $showDateTime;
        return $this;
    }

    function setShowFile( string $showFile ): Log
    {
        $this->showFile = $showFile;
        return $this;
    }

    function setShowTopics( bool $showTopics ): Log
    {
        $this->showTopics = $showTopics;
        return $this;
    }

    /**
     *
     * @var Request
     */
    protected $request;

    /**
     *
     * @var bool
     */
    protected $showDateTime = true;

    /**
     *
     * @var bool
     */
    protected $showFile = true;

    /**
     *
     * @var bool
     */
    protected $showTopics = false;

    /**
     *
     * @var string[]
     */
    protected $acitveTopics = [];

}
