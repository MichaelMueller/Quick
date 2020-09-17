<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface CliParser
{

    /**
     * 
     * @param array $args
     * @return array arguments
     */
    public function parse( array $argv );
}
