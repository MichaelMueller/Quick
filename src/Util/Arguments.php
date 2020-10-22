<?php

namespace Qck\Util;

/**
 *
 * @author muellerm
 */
class CliParser implements \Qck\Interfaces\CliParser
{

    function parse( array $argv )
    {
        if ( count( $argv ) > 1 )
            return parse_str( $argv[ 1 ], $argv );
        else
            return [];
    }

}
