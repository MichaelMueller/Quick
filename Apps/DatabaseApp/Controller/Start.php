<?php

namespace Qck\Apps\DatabaseApp\Controller;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class Start extends \Qck\Apps\DatabaseApp\DatabaseAppContoller
{

  public function proxyRun()
  {
    return new \Qck\Core\HtmlResponse( $this->buildTemplate( new \Qck\Apps\DatabaseApp\Templates\LoginForm() ) );
  }
}
