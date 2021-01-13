<?php

namespace Qck;

/**
 * Class representing the output of a system command
 * 
 * @author Michael Mueller <michaelmuelleronline@gmx.de>
 */
class CmdOutput
{

    function __construct( string $output, int $returnCode )
    {
        $this->output     = $output;
        $this->returnCode = $returnCode;
    }

    function output(): string
    {
        return $this->output;
    }

    function returnCode(): int
    {
        return $this->returnCode;
    }

    public function successful(): bool
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
