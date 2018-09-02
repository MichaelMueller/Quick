<?php

namespace Qck\Apps\DatabaseApp\Controller;
use \Qck\Apps\DatabaseApp\Templates;
use \Qck\Core;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class Login extends \Qck\Apps\DatabaseApp\DatabaseAppContoller
{

  public function proxyRun()
  {
    $Username = $this->getAppConfig();
  }
}
