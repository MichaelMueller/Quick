<?php

namespace Qck\Html;

/**
 * Description of BootstrapPage
 *
 * @author muellerm
 */
class FormLayout implements \Qck\Interfaces\Template
{

  function __construct( \Qck\Interfaces\Router $router, $actionControllerFqcn,
                        \Qck\Interfaces\Html\Page $page )
  {
    $this->router = $router;
    $this->actionControllerFqcn = $actionControllerFqcn;
    $this->page = $page;
  }

  function setMethod( $method )
  {
    $this->method = $method;
  }

  function setTarget( $target )
  {
    $this->target = $target;
  }

  function addFormElement( \Qck\Interfaces\Html\FormElement $formElement )
  {
    $this->formElement[] = $formElement;
  }

  //put your code here
  public function render()
  {
    ob_start();
    $id = $this->actionControllerFqcn;
    $action = $this->router->getLink( $id );
    ?>
    <form id="<?= $id ?>" action="<?= $action ?>" method="<?= $this->method ?>" target="<?= $this->target ?>" >
      <?php
      /* @var $formElement \Qck\Interfaces\Html\FormElement */
      foreach ( $this->formElements as $formElement ):
        $formElement->setPage( $this->page );
        ?>
        <div class="form-group">
          <label for="<?= $formElement->getId() ?>"><?= $formElement->getLabel() ?></label>
        <?= $formElement->render() ?>?>
        </div>
        <?php
      endforeach;
      ?>
    </form>
    <?php
    return ob_get_clean();
  }

  /**
   *
   * @var \Qck\Interfaces\Router 
   */
  protected $router;

  /**
   *
   * @var string 
   */
  protected $actionControllerFqcn;

  /**
   *
   * @var \Qck\Interfaces\Html\Page
   */
  protected $page;

  /**
   *
   * @var string
   */
  protected $method = "POST";

  /**
   *
   * @var string
   */
  protected $target = "_self";

  /**
   *
   * @var array
   */
  protected $formElements = [];

}
