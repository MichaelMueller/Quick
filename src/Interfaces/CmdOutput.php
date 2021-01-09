<?php

namespace Qck\Interfaces;

/**
 * 
 * @author muellerm
 */
interface CmdOutput
{

    /**
     * @return string
     */
    function returnCode();

    /**
     * @return string
     */
    function successful();
    
    /**
     * @return array
     */
    function output();
}
