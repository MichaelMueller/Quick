<?php

namespace Qck;

/**
 *
 * @author mueller
 */
class SimpleCliParser implements Interfaces\CliParser
{
    public function parse( $argv )
    {
        if ( count( $argv ) > 1 )
            return parse_str( $argv[ 1 ], $argv );
        else
            return [];
    }

}
