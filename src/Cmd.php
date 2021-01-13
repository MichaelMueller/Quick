<?php

namespace Qck;

/**
 * Class representing a system command
 * 
 * @author Michael Mueller <michaelmuelleronline@gmx.de>
 */
class Cmd
{

    static function new( string $executable ): Cmd
    {
        return new Cmd( $executable );
    }

    function __construct( string $executable )
    {
        $this->executable = $executable;
    }

    public function arg( $arg ): Cmd
    {
        $this->args[] = $arg;
        return $this;
    }

    public function escapeArg( $arg ): Cmd
    {
        $this->args[] = escapeshellarg( $arg );
        return $this;
    }

    public function run(): CmdOutput
    {
        $args        = [ $this->executable ];
        $args        = array_merge( $args, $this->args );
        $outputArray = [];
        $returnCode  = -1;
        flush();
        exec( implode( " ", $args ), $outputArray, $returnCode );
        return new CmdOutput( implode( "\n", $outputArray ), $returnCode );
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
