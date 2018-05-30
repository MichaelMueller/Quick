<?php

namespace qck\ext\interfaces;

/**
 * represents a user of the system (which gets authenticated in a sense)
 */
interface Authenticator
{

  function check( $Username, $PlainTextPassword );
}
