<?php

namespace Qck\Apps\DatabaseApp;

/**
 *
 * @author muellerm
 */
class UserDb implements \Qck\Interfaces\UserDb, \Qck\Interfaces\Authenticator
{

  public function getUser( $UserName )
  {
    //$this->Db->select( $Select );
    // TODO
    return null;
  }

  public function check( $Username, $PlainTextPassword )
  {
    /* @var $User User */
    $User = $this->getUser( $Username );
    return $this->PasswordHasher->verify( $PlainTextPassword, $User->HashedPassword );
  }

  /**
   *
   * @var \Qck\Sql\Db
   */
  protected $Db;

  /**
   *
   * @var \Qck\Interfaces\PasswordHasher
   */
  protected $PasswordHasher;

}
