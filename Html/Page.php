<?php

namespace Qck\Html;

/**
 * Description of BootstrapPage
 *
 * @author muellerm
 */
class Page implements \Qck\Interfaces\Output, \Qck\Interfaces\Html\Page
{

  function __construct( $title, \Qck\Interfaces\Template $contentTemplate )
  {
    $this->title = $title;
    $this->contentTemplate = $contentTemplate;
  }

  function setLanguage( $language )
  {
    $this->language = $language;
  }

  function setHeaderTemplateCollection( TemplateCollection $headerTemplateCollection )
  {
    $this->headerTemplateCollection = $headerTemplateCollection;
  }

  function setFooterTemplateCollection( TemplateCollection $footerTemplateCollection )
  {
    $this->footerTemplateCollection = $footerTemplateCollection;
  }

  //put your code here
  public function render()
  {
    ob_start();
    ?>
    <!doctype html>
    <html lang="<?= $this->language ?>">
      <head>
        <meta charset="<?= $this->getCharset() ?>">
        <title><?= $this->title ?></title>
        <?php
        foreach ( $this->headerTemplates as $template )
          echo $template->render();
        ?>
      </head>
      <body>
        <?= $this->contentTemplate->render() ?>

        <?php
        foreach ( $this->footerTemplates as $template )
          echo $template->render();
        ?>
      </body>
    </html>
    <?php
    return ob_get_clean();
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

  /**
   *
   * @var string
   */
  protected $title;

  /**
   *
   * @var \Qck\Interfaces\Template
   */
  protected $contentTemplate;

  /**
   *
   * @var string
   */
  protected $language = "en";

  /**
   *
   * @var TemplateCollection
   */
  protected $headerTemplateCollection;

  /**
   *
   * @var TemplateCollection
   */
  protected $footerTemplateCollection;

}
