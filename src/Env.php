<?php

namespace Qck;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class Env implements \Qck\Interfaces\Env
{

  public function getHostName()
  {
    static $var = null;
    if ( !$var )
      $var = gethostname();
    return $var;
  }
}
