<?php

namespace qck\apps\database\controller;

/**
 * Description of databaseController
 *
 * @author muellerm
 */
class Start implements \qck\interfaces\Controller
{
  public function run( \qck\interfaces\AppConfig $config )
  {    
    $Response = new \qck\core\Response;
    $Response->addHeader("Location: ".$config->createLink( "Login" ));
    return $Response;
  }
}
