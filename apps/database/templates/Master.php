<?php

namespace qck\apps\database\templates;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class Master implements \qck\interfaces\Template
{
  function setContentTemplate( \qck\interfaces\Template $ContentTemplate )
  {
    $this->ContentTemplate = $ContentTemplate;
  }

  function setConfig( \qck\interfaces\AppBaseConfig $Config )
  {
    $this->Config = $Config;
  }

    public function render()
  {
    ob_start();
    ?>
    <!DOCTYPE html>
    <html>
      <head>    
        <title><?= $this->Config->getAppName() ?></title>
        <?php
        echo (new Style() )->render();
        ?>
      </head>
      <body>

        <?= $this->ContentTemplate->render() ?>

      </body>
    </html>
    <?php
    return ob_get_clean();
  }

  /**
   *
   * @var \qck\interfaces\Template
   */
  protected $ContentTemplate;
  /**
   *
   * @var \qck\interfaces\AppBaseConfig
   */
  private $Config;

}
