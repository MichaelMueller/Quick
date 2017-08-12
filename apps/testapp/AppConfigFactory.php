<?php

namespace qck\apps\testapp;

/**
 * Description of AppConfigFactory
 *
 * @author micha
 */
class AppConfigFactory implements \qck\interfaces\AppConfigFactory
{

  public function create()
  {
    return new AppConfig();
  }

}
