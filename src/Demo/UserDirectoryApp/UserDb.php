<?php

namespace Qck\Demo\UserDirectoryApp;

class UserDb implements \Qck\Interfaces\UserDb
{

    function __construct( \Qck\PasswordHasher $PasswordHasher )
    {
        $this->PasswordHasher = $PasswordHasher;
    }

    public function getUser( $Username )
    {
        if ($Username == "testuser")
            return new \Qck\User( "testuser", $this->PasswordHasher->createHash( "test" ) );
    }

    /**
     *
     * @var \Qck\PasswordHasher
     */
    protected $PasswordHasher;

}
