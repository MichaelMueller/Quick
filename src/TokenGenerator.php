<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class TokenGenerator implements Interfaces\TokenGenerator
{

    public function create( $Length = 32 )
    {
        //Generate a random string.
        $Token = openssl_random_pseudo_bytes( $Length );

        //Convert the binary data into hexadecimal representation.
        return bin2hex( $Token );
    }

}
