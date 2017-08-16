<?php

namespace qck\html;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class PageCenterLayout extends \qck\interfaces\HtmlElement
{

  function setWidget( \qck\interfaces\HtmlElement $Widget )
  {
    $this->Widget = $Widget;    
  }

  function getWidgets()
  {
    return array ( $this->Widget );
  }

  function getStyles()
  {
    return array ("width"=>"100%", "min-height"=>"100vh", "overflow"=>"visible",
      "display"=>"flex","align-items"=>"center","justify-content"=>"center","flex-wrap"=>"no-wrap");
  }

  private $Widget;

}
