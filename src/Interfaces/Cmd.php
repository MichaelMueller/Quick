<?php

namespace Qck\Interfaces;

/**
 * 
 * @author muellerm
 */
interface Cmd
{

    /**
     * @return Cmd
     */
    function arg( $arg );

    /**
     * @return Cmd
     */
    function escapeArg( $arg );

    /**
     * @return CmdOutput
     */
    function run();
}
