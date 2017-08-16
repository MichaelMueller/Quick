<?php

namespace qck\html;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class Page extends \qck\abstracts\HtmlElement
{

  public function render()
  {
    ob_start();
    ?>
    <!DOCTYPE html>
    <html>
      <head>     
        <title><?= $this->Title ?></title>   
      </head>
      <body<?= $this->getAttributeString() ?>>

        <?= $this->CentralWidget->render() ?>

      </body>
    </html>
    <?php
    return ob_get_clean();
  }

  protected function proxyRender()
  {
    
  }

  function setCentralWidget( \qck\interfaces\HtmlElement $CentralWidget )
  {
    $this->CentralWidget = $CentralWidget;
  }

  function setTitle( $Title )
  {
    $this->Title = $Title;
  }

  /**
   *
   * @var \qck\interfaces\HTMLWidget
   */
  protected $CentralWidget;
  protected $Title;
  protected $Charset = "utf-8";

}
