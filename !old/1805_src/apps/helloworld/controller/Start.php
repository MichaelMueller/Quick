<?php

namespace qck\apps\helloworld\controller;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class Start implements \qck\core\interfaces\Controller
{

  public function run( \qck\core\interfaces\AppConfig $Config )
  {
    /* @var $Config \qck\apps\helloworld\AppConfig */
    return new \qck\core\Response( "Hello World." );
  }
}
