<?php

namespace Qck\Apps\HelloWorld\Controller;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class Start implements \Qck\Interfaces\Controller
{

  public function run( \Qck\Interfaces\AppConfig $Config )
  {
    $Text = "Hello World. Running on " . $Config->getHostName();
    if ( $Config->isCli() )
    {
      print $Text;
      return null;
    }
    else
      return new \Qck\Core\HtmlResponse( new \Qck\Apps\HelloWorld\HelloWorldPage( $Config->getAppName(), $Text ) );
  }
}
