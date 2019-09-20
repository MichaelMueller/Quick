<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Authenticator implements \Qck\Interfaces\Authenticator
{

    function __construct( \Qck\Interfaces\UserDb $UserDb, \Qck\Interfaces\PasswordHasher $PasswordHasher, \Qck\Interfaces\Session $Session )
    {
        $this->UserDb = $UserDb;
        $this->PasswordHasher = $PasswordHasher;
        $this->Session = $Session;
    }

    function setAuthenticatorFactory( \Qck\Interfaces\AuthenticatorFactory $AuthenticatorFactory )
    {
        $this->AuthenticatorFactory = $AuthenticatorFactory;
    }

    public function check( $Username, $PlainTextPassword )
    {
        $CredentialsOk = false;
        $User = $this->UserDb->getUser( $Username );

        if ($User)
        {
            // Use a custom Authenticator?
            if ($this->AuthenticatorFactory)
            {
                $AuthenticatorName = $User->getAuthenticatorName();
                $Authenticator = $this->AuthenticatorFactory->createAuthenticator( $AuthenticatorName );
                $CredentialsOk = $Authenticator->check( $Username, $PlainTextPassword );
            }
            else
                $CredentialsOk = $this->PasswordHasher->verify( $PlainTextPassword, $User->getHashedPassword() );
        }
        if ($CredentialsOk)
        {
            $this->Session->startSession( $Username );
        }

        return $CredentialsOk;
    }

    /**
     *
     * @var \Qck\Interfaces\UserDb
     */
    protected $UserDb;

    /**
     *
     * @var \Qck\Interfaces\PasswordHasher
     */
    protected $PasswordHasher;

    /**
     *
     * @var \Qck\Interfaces\Session
     */
    protected $Session;

    /**
     *
     * @var \Qck\Interfaces\AuthenticatorFactory
     */
    protected $AuthenticatorFactory;

}
