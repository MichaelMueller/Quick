<?php

namespace Qck\Apps\DatabaseApp\Controller;

use \Qck\Apps\DatabaseApp\Templates;
use \Qck\Core;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class Start extends \Qck\Apps\DatabaseApp\DatabaseAppContoller
{

  public function proxyRun()
  {
    $LoginForm = new Templates\LoginForm( $this->getAppConfig()->getAppName(), $this->getAppConfig()->getRouter()->getLink( "Login" ) );
    return new Core\HtmlResponse( $this->buildTemplate($LoginForm) );
  }
}
