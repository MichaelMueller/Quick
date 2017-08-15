<?php

namespace qck\html;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class FlexLayout implements \qck\interfaces\HtmlLayout
{

  const COLUMN = "column";
  const ROW = "row";
  const HALIGN_START = "flex-start";
  const HALIGN_CENTER = "center";
  const HALIGN_END = "flex-end";
  const VALIGN_START = "flex-start";
  const VALIGN_CENTER = "center";
  const VALIGN_END = "flex-end";
  const VALIGN_STRETCH = "stretch";

  function __construct()
  {
    $this->Styles[ "display" ] = "flex";
    $this->Styles[ "flex-wrap" ] = "wrap";
  }

  function setHAlign( $halign = self::HALIGN_START )
  {
    $this->Styles[ "justify-content" ] = $halign;
  }

  function setVAlign( $valign = self::VALIGN_START )
  {
    $this->Styles[ "align-items" ] = $valign;
  }
  
  function setDirection( $Direction = self::COLUMN )
  {
    $this->Styles[ "flex-direction" ] = $Direction;
  }

  function addWidget( \qck\interfaces\HtmlElement $Widget, $GrowFactor = null, $Margin=null )
  {
    $this->Widgets[] = $Widget;
    if ( $GrowFactor )
      $Widget->setStyle( "flex", $GrowFactor );
    if ( $Margin )
      $Widget->setStyle( "margin", $Margin );
  }

  function getWidgets()
  {
    return $this->Widgets;
  }

  function getStyles()
  {
    return $this->Styles;
  }

  private $Widgets = array ();
  private $Styles = array ();

}
