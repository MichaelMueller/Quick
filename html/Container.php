<?php

namespace qck\html;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class Container extends \qck\abstracts\HtmlElement
{
  function setWidth( $width )
  {
    $this->setStyle( "width", $width );
  }

  function setMinHeight( $height )
  {
    $this->setStyle( "min-height", $height );
  }

  function setHeight( $height )
  {
    $this->setStyle( "height", $height );
  }

  function setLayout( \qck\interfaces\HtmlLayout $Layout )
  {
    $this->Layout = $Layout;
  }

  protected function getStyles()
  {
    return array_merge(parent::getStyles(), $this->Layout->getStyles());
  }
  
  public function renderInternal()
  {
    $Widgets = $this->Layout->getWidgets();
    ob_start();
    foreach ( $Widgets as $Widget )
      echo $Widget->render();
    return ob_get_clean();
  }

  protected function hasValue()
  {
    return true;
  }

  /**
   *
   * @var \qck\interfaces\HtmlLayout
   */
  private $Layout;

}
