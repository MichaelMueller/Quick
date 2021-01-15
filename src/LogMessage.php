<?php

namespace Qck;

/**
 * Class representing a log message.
 * 
 * @author Michael Mueller <michaelmuelleronline@gmx.de>
 */
class LogMessage
{

    const ALL   = "all";
    const INFO  = "info";
    const WARN  = "warn";
    const ERROR = "error";
    const DEBUG = "debug";

    static function new( Log $log, string $text, string $topic ): LogMessage
    {
        return new LogMessage( $log, $text, $topic );
    }

    function __construct( Log $log, string $text, string $topic )
    {
        $this->log  = $log;
        $this->text = $text;
        $this->addTopic( $topic );
        $this->addTopic( self::ALL );

        $date           = \DateTime::createFromFormat( 'U.u', microtime( TRUE ) );
        if ( $date )
            $this->dateTime = $date->format( 'Y-m-d H:i:s.u' );
        $trace          = debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS, 5 );
        if ( count( $trace ) > 3 )
        {
            $this->traceThirdElement = $trace[ 3 ];

            if ( count( $trace ) > 4 && isset( $trace[ 4 ][ "class" ] ) )
                $this->addTopic( $trace[ 4 ][ "class" ] );
        }
    }

    function send()
    {
        $this->log->send( $this );
    }

    function addTopic( $topic ): LogMessage
    {
        $this->topics[] = $topic;
        return $this;
    }

    function topics(): array
    {
        return $this->topics;
    }

    function hasTopic( $topic ): bool
    {
        return in_array( $topic, $this->topics );
    }

    function addArg( $arg ): LogMessage
    {
        $this->args[] = $arg;
        return $this;
    }

    function setShowDateTime( bool $showDateTime ): LogMessage
    {
        $this->showDateTime = $showDateTime;
        return $this;
    }

    function setShowFile( string $showFile ): LogMessage
    {
        $this->showFile = $showFile;
        return $this;
    }

    function setShowTopics( bool $showTopics ): LogMessage
    {
        $this->showTopics = $showTopics;
        return $this;
    }

    function disableAdditionalInformation(): LogMessage
    {
        $this->showDateTime = false;
        $this->showTopics   = false;
        $this->showFile     = false;
        return $this;
    }

    function text()
    {

        $msg = "";
        if ( $this->showTopics )
            $msg .= "[" . implode( ",", $this->topics ) . "]";
        if ( $this->showDateTime )
            $msg .= "[" . $this->dateTime . "]";
        if ( $this->showFile )
            $msg .= "[" . pathinfo( $this->traceThirdElement[ "file" ], PATHINFO_BASENAME ) . ":" . $this->traceThirdElement[ "line" ] . "]";
        $msg .= ($msg != "" ? ": " : null) . vsprintf( $this->text, $this->args );
        return $msg;
    }

    function __toString()
    {
        return $this->text();
    }

    /**
     *
     * @var Log
     */
    protected $log;

    /**
     *
     * @var string
     */
    protected $text;

    /**
     *
     * @var string[]
     */
    protected $args;

    /**
     *
     * @var string
     */
    protected $topics;

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


    // state

    /**
     *
     * @var string
     */
    private $dateTime;

    /**
     *
     * @var array
     */
    private $traceThirdElement;

}
