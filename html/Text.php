<?php

namespace qck\html;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class Text extends \qck\abstracts\HtmlElement
{

  public function proxyRender()
  {
    return $this->Text;
  }

  function setText( $Text )
  {
    $this->Text = $Text;
  }

  function __construct( $Text )
  {
    $this->setTag("p");
    $this->Text = $Text;
  }

  private $Text;

}
