<?php

namespace Qck;

class CmdOutput
{

    function __construct(string $output, int $returnCode)
    {
        $this->output = $output;
        $this->returnCode = $returnCode;
    }

    function output()
    {
        return $this->output;
    }

    function returnCode()
    {
        return $this->returnCode;
    }

    public function successful()
    {
        return $this->returnCode == 0;
    }

    /**
     *
     * @var string
     */
    protected $output;

    /**
     *
     * @var int
     */
    protected $returnCode;

}
