<?php

namespace Qck\Interfaces;

/**
 * Interface for a Path object
 * @author muellerm
 */
interface AuthenticatorFactory
{

    /**
     * @return Authenticator
     */
    function createAuthenticator( $Name );
}
