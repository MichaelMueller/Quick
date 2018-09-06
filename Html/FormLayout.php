<?php

namespace Qck\Html;

/**
 * Description of BootstrapPage
 *
 * @author muellerm
 */
class FormLayout implements \Qck\Interfaces\Template
{

  function __construct( $actionControllerFqcn, \Qck\Interfaces\Router $router,
                        \Qck\Interfaces\Html\Page $page )
  {
    $this->actionControllerFqcn = $actionControllerFqcn;
    $this->router = $router;
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
    $this->formElements[] = $formElement;
  }

  //put your code here
  public function render()
  {
    ob_start();
    $id = $this->router->getQuery();
    $action = $this->router->getLink( $this->actionControllerFqcn );
    ?>
    <form id="<?= $id ?>" action="<?= $action ?>" method="<?= $this->method ?>" target="<?= $this->target ?>" >
      <?php
      /* @var $formElement \Qck\Interfaces\Html\FormElement */
      foreach ( $this->formElements as $formElement ):
        ?>
        <div class="form-group">
          <label for="<?= $formElement->getName() ?>"><?= $formElement->getLabel() ?></label>
          <?= $formElement->createInputElement( $this->page )->render() ?>
        </div>
        <?php
      endforeach;
      ?>
      <button type="submit" class="btn btn-primary">Submit</button>      
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
