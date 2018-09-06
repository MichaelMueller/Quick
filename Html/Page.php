<?php

namespace Qck\Html;

/**
 * Description of BootstrapPage
 *
 * @author muellerm
 */
class Page implements \Qck\Interfaces\Output, \Qck\Interfaces\Html\Page
{

  function __construct( $title, \Qck\Interfaces\Template $contentTemplate = null )
  {
    $this->title = $title;
    $this->contentTemplate = $contentTemplate;
  }

  function setContentTemplate( \Qck\Interfaces\Template $contentTemplate )
  {
    $this->contentTemplate = $contentTemplate;
  }

  //put your code here
  public function render()
  {
    ob_start();
    ?>
    <!doctype html>
    <html lang="en">

      <!-- MetaElements -->
      <meta charset="<?= $this->getCharset() ?>">
      <?php
      /* @var $element Element */
      foreach ( $this->metaElements as $element )
        echo $element->render() . PHP_EOL;
      ?>
      <!-- /MetaElements -->

      <!-- Stylesheets -->
      <?php
      /* @var $element Element */
      foreach ( $this->styleSheets as $element )
        echo $element->render() . PHP_EOL;
      ?>
      <!-- /Stylesheets -->

      <title><?= $this->title ?></title>

      <body class="<?= implode( " ", $this->bodyCssClasses ) ?>">
        <?= $this->contentTemplate->render() ?>

        <!-- Scripts -->
        <?php
        /* @var $element Element */
        foreach ( $this->scripts as $element )
          echo $element->render() . PHP_EOL;
        ?>
        <!-- /Scripts -->
    </html>
    <?php
    return ob_get_clean();
  }

  public function addBodyCssClass( $cssClass )
  {
    $this->bodyCssClasses[] = $cssClass;
  }

  public function addMetaElement( $name, $content )
  {
    $this->styleSheets[] = new Element( "meta", [ "name" => $name, "content" => $content ] );
  }

  public function addStyleSheet( $href, $integrity = null, $crossorigin = null )
  {
    $atts = [ "rel" => "stylesheet", "href" => $href ];
    if ( $integrity )
      $atts[ "integrity" ] = $integrity;
    if ( $crossorigin )
      $atts[ "crossorigin" ] = $crossorigin;
    $this->styleSheets[] = new Element( "link", $atts );
  }

  public function addScript( $src, $integrity = null, $crossorigin = null )
  {
    $atts = [ "rel" => "stylesheet", "src" => $src ];
    if ( $integrity )
      $atts[ "integrity" ] = $integrity;
    if ( $crossorigin )
      $atts[ "crossorigin" ] = $crossorigin;
    $this->scripts[] = new Element( "script", $atts, "" );
  }

  public function getAdditionalHeaders()
  {
    return [];
  }

  public function getCharset()
  {
    return \Qck\Interfaces\Output::CHARSET_UTF_8;
  }

  public function getContentType()
  {
    return \Qck\Interfaces\Output::CONTENT_TYPE_TEXT_HTML;
  }

  public function createElement( $name, array $attributes = array (), $text = null )
  {
    return new Element( $name, $attributes, $text );
  }

  protected $title;
  protected $metaElements = [];
  protected $styleSheets = [];
  protected $scripts = [];
  protected $bodyCssClasses = [];

  /**
   *
   * @var \Qck\Interfaces\Template
   */
  protected $contentTemplate;

}
