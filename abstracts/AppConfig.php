<?php

namespace qck\abstracts;

/**
 * Description of MbitsPhpMailer
 *
 * @author muellerm
 */
abstract class AppConfig implements \qck\interfaces\AppConfig
{
  public function getHostInfo()
  {
    static $var = null;
    if ( !$var )
      $var = gethostname();
    return $var;
  }

  function getAdminMailer()
  {
    return null;
  }

  public function sendMailOnException()
  {
    return false;
  }

  function getSetupController()
  {
    return null;
  }
}
