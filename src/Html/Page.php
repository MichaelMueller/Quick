<?php

namespace Qck\Html;

/**
 * App class is essentially the class to start. It is the basic error handler. No code besides the require statement and initialization should be called in any app before.
 * 
 * @author muellerm
 */
class Page implements \Qck\Interfaces\Html\Snippet, \Qck\Interfaces\Output
{

  function __construct($Title, $BodyTemplateOrText)
  {
    $this->Title              = $Title;
    $this->BodyTemplateOrText = $BodyTemplateOrText;
  }

  function setHeaderSnippet(\Qck\Interfaces\Html\Snippet $HeaderSnippet)
  {
    $this->HeaderSnippet = $HeaderSnippet;
  }

  function setFooterSnippet(\Qck\Interfaces\Html\Snippet $FooterSnippet)
  {
    $this->FooterSnippet = $FooterSnippet;
  }

  function setLanguageProvider(\Qck\Interfaces\LanguageProvider $LanguageProvider)
  {
    $this->LanguageProvider = $LanguageProvider;
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

  public function renderHtml()
  {
    ob_start();
    $lang = " " . $this->LanguageProvider ? $this->LanguageProvider->getLanguage() : null;
    ?>
    <!doctype html>
    <html<?= $lang ?>>
      <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?= $this->Title ?></title>
        <?= isset($this->HeaderSnippet) ? $this->HeaderSnippet->renderHtml() : null ?>
      </head>
      <body>
        <?= is_string($this->BodyTemplateOrText) ? $this->BodyTemplateOrText : $this->BodyTemplateOrText->renderHtml() ?>

    <?= isset($this->FooterSnippet) ? $this->FooterSnippet->renderHtml() : null ?>
      </body>
    </html>
    <?php
    return ob_get_clean();
  }

  protected function printAttributeIfSet($attName, $attValue, $addPrependingSpace = true)
  {
    echo is_null($attValue) ? "" : ($addPrependingSpace ? " " : "") . $attName . '="' . $attValue . '"';
  }

  public function setTitle($Title)
  {
    $this->Title = $Title;
  }

  /**
   *
   * @var string
   */
  protected $Title;

  /**
   *
   * @var mixed
   */
  protected $BodyTemplateOrText;

  /**
   *
   * @var \Qck\Interfaces\Html\Snippet
   */
  protected $HeaderSnippet;

  /**
   *
   * @var \Qck\Interfaces\Html\Snippet
   */
  protected $FooterSnippet;

  /**
   *
   * @var \Qck\Interfaces\LanguageProvider
   */
  protected $LanguageProvider;

}
