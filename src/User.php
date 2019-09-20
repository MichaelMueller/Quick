<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class User implements \Qck\Interfaces\User
{

    function __construct( $Username, $HashedPassword, $AuthenticatorName = null )
    {
        $this->Username = $Username;
        $this->HashedPassword = $HashedPassword;
        $this->AuthenticatorName = $AuthenticatorName;
    }

    function getUsername()
    {
        return $this->Username;
    }

    function getHashedPassword()
    {
        return $this->HashedPassword;
    }

    function getAuthenticatorName()
    {
        return $this->AuthenticatorName;
    }

    protected $HashedPassword;
    protected $AuthenticatorName;

}
