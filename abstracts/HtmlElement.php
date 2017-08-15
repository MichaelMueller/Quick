<?php

namespace qck\abstracts;

/**
 * a Controller that only runs for logged in users
 *
 * @author muellerm
 */
abstract class HtmlElement implements \qck\interfaces\HtmlElement
{

  abstract protected function proxyRender();

  function render()
  {
    $elment = "<" . $this->Tag . $this->getAttributeString();
    if ( $this->NoClosingElement )
      $elment .= "/>";
    else
      $elment .= ">" . PHP_EOL . "  " . ($this->proxyRender()) . PHP_EOL . "</" . $this->Tag . ">" . PHP_EOL;

    return $elment;
  }

  protected function setTag( $Tag )
  {
    $this->Tag = $Tag;
  }

  protected function setNoClosingElement( $NoClosingElement )
  {
    $this->NoClosingElement = $NoClosingElement;
  }

  public function setStyle( $name, $value )
  {
    $this->Styles[ $name ] = $value;
  }

  protected function getStyles()
  {
    return $this->Styles;
  }

  protected function getAttributeString()
  {
    $styles = $this->getStyles();
    if ( count( $styles ) > 0 )
      $this->setAttribute( "style", $this->implode( $styles, ": ", "; " ) );
    $attributes = $this->Attributes;
    $attributeString = $this->implode( $attributes, "=", " " );
    return $attributeString ? " ".$attributeString: "";
  }

  protected function setAttribute( $name, $value )
  {
    $this->Attributes[ $name ] = '"' . $value . '"';
  }

  protected function implode( $map, $glueKeyValue = "=", $glueItems = "&" )
  {
    $String = "";
    $toEnd = count( $map );
    foreach ( $map as $key => $value )
    {
      $String .= $key . $glueKeyValue . $value;
      if ( 0 !== --$toEnd )
        $String .= $glueItems;
    }
    return $String;
  }

  private $Styles = array();// array ( "min-width" => "240px" );
  private $Attributes = array ();
  private $Tag = "div";
  private $NoClosingElement = false;

}
