<?php

namespace Qck\Apps\DatabaseApp;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
abstract class DatabaseAppContoller extends \Qck\Ext\ExtController
{

  protected function buildTemplate( \Qck\Interfaces\Template $Template )
  {
    return new Templates\Page( $this->getAppConfig()->getAppName(), $Template );
  }
}
