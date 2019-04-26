<?php

namespace Qck;

/**
 * @author muellerm
 */
class AppConfig implements Interfaces\AppConfig
{

    function __construct(Interfaces\CliDetector $CliDetector, Interfaces\Inputs $Inputs)
    {
        $this->CliDetector = $CliDetector;
        $this->Inputs = $Inputs;
    }

    function getCliDetector()
    {
        return $this->CliDetector;
    }

    function getInputs()
    {
        return $this->Inputs;
    }

    /**
     *
     * @var Interfaces\CliDetector
     */
    protected $CliDetector;

    /**
     *
     * @var Interfaces\Inputs
     */
    protected $Inputs;

}
