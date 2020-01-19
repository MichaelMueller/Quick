<?php

namespace Qck\Interfaces;

/**
 * Basically implements a Session mechanism to get the current user logged in
 * 
 * @author muellerm
 */
interface CurrentUserService
{

    /**
     * @return User or null
     */
    function getCurrentUser();
}
