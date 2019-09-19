<?php

namespace Qck;

/**
 * App class is essentially the class to start. It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class CliDetector implements Interfaces\CliDetector
{

    function __construct($Cli = null)
    {
        $this->Cli = $Cli;
    }

    function isCli()
    {
        if (is_null($this->Cli))
        {
            $this->Cli = false;
            if (defined('STDIN'))
            {
                $this->Cli = true;
            } else if (empty($_SERVER['REMOTE_ADDR']) and ! isset($_SERVER['HTTP_USER_AGENT']) and count($_SERVER['argv']) > 0)
            {
                $this->Cli = true;
            }
        }
        return $this->Cli;
    }

    protected $Cli;

}
