<?php

namespace Qck\Demo\AuthenticationDemo;

class UserDb implements \Qck\Interfaces\UserDb
{

    function __construct( \Qck\PasswordHasher $PasswordHasher )
    {
        $this->PasswordHasher = $PasswordHasher;
    }

    public function getUser( $Username )
    {
        if ($Username == "test")
            return new \Qck\User( "test", $this->PasswordHasher->createHash( "test" ) );
    }

    /**
     *
     * @var \Qck\PasswordHasher
     */
    protected $PasswordHasher;

}
