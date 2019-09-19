<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Arguments implements Interfaces\Arguments
{

    function __construct( array $data = [] )
    {
        $this->createData( $data );
    }

    function getData()
    {
        return $this->Data;
    }

    public function get( $Name, $Default = null )
    {
        return isset( $this->Data[$Name] ) ? $this->Data[$Name] : $Default;
    }

    public function has( $Name )
    {
        return isset( $this->Data[$Name] );
    }

    public function isHttpRequest()
    {
        static $HttpRequest = null;
        if (is_null( $HttpRequest ))
            $HttpRequest = http_response_code() !== null;
        return $HttpRequest;
    }

    protected function createData( array $data = [] )
    {
        if ($this->isHttpRequest())
            $this->Data = array_merge( $this->Data, $_REQUEST, $_FILES );
        else
            $this->Data = $this->parseArgv( $_SERVER["argv"] );

        $this->Data = array_merge( $this->Data, $data );
    }

    protected function parseArgv( array $argv )
    {
        $argvData = [];
        $currKey = null;
        $currVal = null;
        for ($i = 1; $i < count( $argv ); $i++)
        {
            $val = $argv[$i];
            $key = null;
            if (mb_strlen( $val ) > 2 && $val[0] == "-" && $val[1] == "-")
                $key = mb_substr( $val, 2 );
            else if (mb_strlen( $val ) > 1 && $val[0] == "-")
                $key = mb_substr( $val, 1 );
            else
                $currVal = $val;

            if ($key)
            {
                if ($currKey)
                    $argvData[$currKey] = true;
                $currKey = $key;
            }

            if ($currKey && $currVal !== null)
            {
                if (isset( $argvData[$currKey] ) && !is_bool( $argvData[$currKey] ) && !is_array( $argvData[$currKey] ))
                    $argvData[$currKey] = [$argvData[$currKey]];
                if (isset( $argvData[$currKey] ) && is_array( $argvData[$currKey] ))
                    $argvData[$currKey][] = $currVal;
                else
                    $argvData[$currKey] = $currVal;
                $currVal = null;
                $currKey = null;
            }
        }
        return $argvData;
    }

    /**
     *
     * @var array
     */
    protected $Data = [];

}
