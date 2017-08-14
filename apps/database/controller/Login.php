<?php

namespace qck\apps\database\controller;

/**
 * Description of databaseController
 *
 * @author muellerm
 */
class Login implements \qck\interfaces\Controller
{

  public function run( \qck\interfaces\AppConfig $config )
  {
    /* @var $config \qck\apps\database\AppConfig */
    $Page = new \qck\bootstrap\Page();
    $Login = new \qck\bootstrap\Login();
    $Login->setFormActionUrl($config->createLink("CheckLogin"));
    $Page->setBodyTemplate( $Login );
    $Page->setTitle($config->getAppName());
    return new \qck\core\Response( $Page );
  }
}
