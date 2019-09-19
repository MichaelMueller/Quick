<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Arguments implements \Qck\Interfaces\Arguments
{

    function __construct( CliDetector $CliDetector, $Data = [] )
    {
        $this->CliDetector = $CliDetector;
        $this->Data        = $Data;
    }

    function setData( $Data )
    {
        $this->Data = $Data;
    }

    public function get( $Name, $Default = null )
    {
        $Data = $this->getData();
        return isset( $Data[ $Name ] ) ? $Data[ $Name ] : $Default;
    }

    protected function parseArgv()
    {
        $argv    = $_SERVER[ 'argv' ];
        $Data    = [];
        $currKey = null;
        $currVal = null;
        for ( $i = 1; $i < count( $argv ); $i++ )
        {
            $val     = $argv[ $i ];
            $key     = null;
            if ( mb_strlen( $val ) > 2 && $val[ 0 ] == "-" && $val[ 1 ] == "-" )
                $key     = mb_substr( $val, 2 );
            else if ( mb_strlen( $val ) > 1 && $val[ 0 ] == "-" )
                $key     = mb_substr( $val, 1 );
            else
                $currVal = $val;

            if ( $key )
            {
                if ( $currKey )
                    $Data[ $currKey ] = true;
                $currKey          = $key;
            }

            if ( $currKey && $currVal !== null )
            {
                if ( isset( $Data[ $currKey ] ) && !is_bool( $Data[ $currKey ] ) && !is_array( $Data[ $currKey ] ) )
                    $Data[ $currKey ]   = [ $Data[ $currKey ] ];
                if ( isset( $Data[ $currKey ] ) && is_array( $Data[ $currKey ] ) )
                    $Data[ $currKey ][] = $currVal;
                else
                    $Data[ $currKey ]   = $currVal;
                $currVal            = null;
                $currKey            = null;
            }
        }
        return $Data;
    }

    public function has( $Name )
    {
        $Data = $this->getData();
        return isset( $Data[ $Name ] );
    }

    public function getData()
    {
        if ( !$this->Merged )
        {
            $InputData    = $this->CliDetector->isCli() ? $this->parseArgv() : $_REQUEST;
            $this->Data   = array_merge( $InputData, $this->Data );
            $this->Merged = true;
        }
        return $this->Data;
    }

    /**
     *
     * @var CliDetector
     */
    protected $CliDetector;

    /**
     *
     * @var array
     */
    protected $Data;

    /**
     *
     * @var bool
     */
    protected $Merged = False;

}
