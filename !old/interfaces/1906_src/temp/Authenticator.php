<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface Authenticator
{

  /**
   * 
   * @param string $Username
   * @param string $PlainTextPassword
   * @return void
   */
  public function check( $Username, $PlainTextPassword );

}
