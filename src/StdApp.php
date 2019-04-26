<?php

namespace Qck;

/**
 * App class is essentially the class to start.
 * It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class StdApp implements Interfaces\Functor
{

    function __construct($ProjectDir)
    {
        $this->ProjectDir = $ProjectDir;
    }

    public function run()
    {
        
    }

    /**
     *
     * @var bool 
     */
    protected $ProjectDir;

}
