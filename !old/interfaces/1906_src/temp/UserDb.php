<?php

namespace Qck\Interfaces;

/**
 *
 * Basic and simple interface for a controller
 * @author muellerm
 */
interface UserDb
{

    /**
     * @return User
     */
    function getUser( $Username );
}
