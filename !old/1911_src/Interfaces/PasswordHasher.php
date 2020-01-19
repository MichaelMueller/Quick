<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface PasswordHasher
{

    /**
     * 
     * @param string $PlainTextPassword
     * @param string $HashedPassword
     */
    function verify( $PlainTextPassword, $HashedPassword );

    /**
     * 
     * @param string $plainTextPassword
     */
    function createHash( $plainTextPassword );
}
