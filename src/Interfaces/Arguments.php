<?php

namespace Qck\Interfaces;

/**
 * Encapsulation of everything that is known when a request is sent to this system (input
 * variables, env, client and config infos)
 * @author muellerm
 */
interface Arguments extends ImmutableDict, HttpRequest
{
    
}
