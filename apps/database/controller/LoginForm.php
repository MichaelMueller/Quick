<?php

namespace qck\apps\database\controller;

/**
 * Description of databaseController
 *
 * @author muellerm
 */
class LoginForm implements \qck\interfaces\Controller
{
  public function run( \qck\interfaces\AppConfig $config )
  {
    /* @var $config \qck\apps\database\AppConfig */
    $LoginForm = new \qck\apps\database\templates\LoginForm();
    $LoginForm->setConfig($config);
    $Master = new \qck\apps\database\templates\Master();
    $Master->setConfig($config);
    $Master->setContentTemplate( $LoginForm );
    return new \qck\core\Response( $Master );
  }
}
