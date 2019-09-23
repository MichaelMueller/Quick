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
            $HttpRequest = !isset( $_SERVER["argv"] ) || is_null( $_SERVER["argv"] ) || is_string( $_SERVER["argv"] );
        return $HttpRequest;
    }

    protected function createData( array $data = [] )
    {
        if ($this->isHttpRequest())
            $this->Data = array_merge( $this->Data, $_REQUEST );
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

    function getBestSupportedMimeType( $mimeTypes = null )
    {
        // Values will be stored in this array
        $AcceptTypes = Array();

        // Accept header is case insensitive, and whitespace isn’t important
        $accept = strtolower( str_replace( ' ', '', $_SERVER['HTTP_ACCEPT'] ) );
        // divide it into parts in the place of a ","
        $accept = explode( ',', $accept );
        foreach ($accept as $a)
        {
            // the default quality is 1.
            $q = 1;
            // check if there is a different quality
            if (strpos( $a, ';q=' ))
            {
                // divide "mime/type;q=X" into two parts: "mime/type" i "X"
                list($a, $q) = explode( ';q=', $a );
            }
            // mime-type $a is accepted with the quality $q
            // WARNING: $q == 0 means, that mime-type isn’t supported!
            $AcceptTypes[$a] = $q;
        }
        arsort( $AcceptTypes );

        // if no parameter was passed, just return parsed data
        if (!$mimeTypes)
            return $AcceptTypes;

        $mimeTypes = array_map( 'strtolower', (array) $mimeTypes );

        // let’s check our supported types:
        foreach ($AcceptTypes as $mime => $q)
        {
            if ($q && in_array( $mime, $mimeTypes ))
                return $mime;
        }
        // no mime-type found
        return null;
    }

    /**
     *
     * @var array
     */
    protected $Data = [];

}
