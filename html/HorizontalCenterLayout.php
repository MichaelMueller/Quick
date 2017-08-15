<?php

namespace qck\html;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class HorizontalCenterLayout implements \qck\interfaces\HtmlLayout
{

  function setWidget( \qck\interfaces\HtmlElement $Widget )
  {
    $this->Widget = $Widget;
    $Widget->setStyle( "margin-right", "auto" );
    $Widget->setStyle( "margin-left", "auto" );
  }

  function getWidgets()
  {
    return array ( $this->Widget );
  }

  function getStyles()
  {
    return array ();
  }

  private $Widget;

}
