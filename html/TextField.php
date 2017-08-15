<?php

namespace qck\html;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class TextField extends \qck\abstracts\HtmlElement
{

  public function proxyRender()
  {
  }

  function __construct( $Name, $Text )
  {
    $this->setTag("input");
    $this->setAttribute("id", $Name);
    $this->setAttribute("name", $Name);
    $this->setAttribute("value", $Text);
    $this->setAttribute("type", "text");
    $this->setNoClosingElement(true);
  }

}
