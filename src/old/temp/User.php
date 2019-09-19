<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class User implements \Qck\Interfaces\User
{

    function setUsername( $Username )
    {
        $this->Username = $Username;
    }

    function setHashedPassword( $HashedPassword )
    {
        $this->HashedPassword = $HashedPassword;
    }

    function setAuthenticator( $Authenticator )
    {
        $this->Authenticator = $Authenticator;
    }

    function setAdmin( $Admin )
    {
        $this->Admin = $Admin;
    }

    public function getAuthenticator()
    {
        return $this->Authenticator;
    }

    public function getHashedPassword()
    {
        return $this->HashedPassword;
    }

    public function getUsername()
    {
        return $this->Username;
    }

    public function isAdmin()
    {
        return $this->Admin;
    }

    protected $Username;
    protected $HashedPassword;
    protected $Authenticator;
    protected $Admin;

}
