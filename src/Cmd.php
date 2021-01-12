<?php

namespace Qck;

class Cmd
{

    function __construct(string $executable)
    {
        $this->executable = $executable;
    }

    public function arg($arg)
    {
        $this->args[] = $arg;
        return $this;
    }

    public function escapeArg($arg)
    {
        $this->args[] = escapeshellarg($arg);
        return $this;
    }

    public function run()
    {
        $args = [$this->executable];
        $args = array_merge($args, $this->args);
        $outputArray = [];
        $returnCode = -1;
        flush();
        exec(implode(" ", $args), $outputArray, $returnCode);
        return new CmdOutput(implode("\n", $outputArray), $returnCode);
    }

    /**
     *
     * @var string
     */
    protected $executable;

    /**
     *
     * @var string[]
     */
    protected $args = [];

}
