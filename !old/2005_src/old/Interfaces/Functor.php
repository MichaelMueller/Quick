<?php

namespace Qck\Interfaces;

/**
 * A basic interface for everything that can be echoed
 * @author muellerm
 */
interface Functor
{

    /**
     * @return void
     */
    public function __invoke();
}
