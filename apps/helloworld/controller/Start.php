<?php

namespace qck\apps\helloworld\controller;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class Start implements \qck\interfaces\Controller
{
  public function run( \qck\interfaces\AppConfig $config )
  {    
    return new \qck\core\Response("Hello World.");
  }
}
