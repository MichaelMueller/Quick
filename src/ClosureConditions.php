<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class ClosureConditions implements \Qck\Interfaces\Conditions
{

    static function create( callable $closure )
    {
        return new ClosureConditions( $closure );
    }

    function __construct( callable $closure )
    {
        $this->closure = $closure;
    }

    public function areMetBy( $array ): bool
    {
        return ($this->closure)( $array );
    }

    /**
     *
     * @var callable
     */
    protected $closure;

}
