<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Authenticator implements Interfaces\Authenticator
{

    function __construct( Interfaces\UserDb $UserDb, Interfaces\PasswordHasher $PasswordHasher, Interfaces\AuthenticatorFactory $AuthenticatorFactory = null )
    {
        $this->UserDb               = $UserDb;
        $this->PasswordHasher       = $PasswordHasher;
        $this->AuthenticatorFactory = $AuthenticatorFactory;
    }

    public function check( $Username, $PlainTextPassword )
    {
        $User                = $this->UserDb->getUser( $Username );
        if ( !$User )
            return false;
        // Use a custom Authenticator?
        $CustomAuthenticator = $this->AuthenticatorFactory ? $this->AuthenticatorFactory->create( $User->getAuthenticatorName() ) : null;
        if ( $CustomAuthenticator )
            return $CustomAuthenticator->check( $Username, $PlainTextPassword );
        else
        {
            return $this->PasswordHasher->verify( $PlainTextPassword, $User->getHashedPassword() ) === true;
        }
    }

    /**
     *
     * @var Interfaces\UserDb
     */
    protected $UserDb;

    /**
     *
     * @var Interfaces\PasswordHasher
     */
    protected $PasswordHasher;

    /**
     *
     * @var Interfaces\AuthenticatorFactory
     */
    protected $AuthenticatorFactory;

}
