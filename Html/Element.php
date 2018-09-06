<?php

namespace Qck\Html;

/**
 * Description of BootstrapPage
 *
 * @author muellerm
 */
class Element implements \Qck\Interfaces\Template
{

  function __construct( $name, array $attributes = [], $text = null )
  {
    $this->name = $name;
    $this->attributes = $attributes;
    $this->text = $text;
  }

  public function render()
  {
    $output = "<" . $this->name;
    foreach ( $this->attributes as $key => $value )
    {
      $output .= " " . $key . ($value ? '="' . $value . '"' : "");
    }
    $output .= ">";
    if ( $this->text !== null )
      $output .= $this->text . "</" . $this->name . ">";
    return $output;
  }

  public function getName()
  {
    return $this->name;
  }

  public function setAttribute( $name, $value )
  {
    $this->attributes[ $name ] = $value;
  }

  public function setText( $text )
  {
    $this->text = $text;
  }

  protected $name;
  protected $text;

  /**
   *
   * @var array
   */
  protected $attributes;

}
