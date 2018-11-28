<?php

namespace Qck\Html;

/**
 * 
 * @author muellerm
 */
class LoginForm implements \Qck\Interfaces\Html\Snippet
{

  public function renderHtml()
  {
    ob_start();
    ?>
    <form id="LoginForm" action="<?= $this->Action ?>">
      <img src="<?= $this->Logo ?>">
      <span><?= $this->Title ?></span>
      <label for="<?= $this->UserNameElement->getId() ?>"><?= $this->UserNameElement->getLabel() ?></label>
      <?= $this->UserNameElement->renderHtml() ?>
      <label for="<?= $this->PasswordElement->getId() ?>">Password: </label>
      <?= $this->PasswordElement->renderHtml() ?>      
      <input type="submit">
    </form>		

    <?php
    return ob_get_clean();
  }

  public function getLoginForm( $Action, $UserNameElement, $PasswordElement, $Title, $Logo )
  {
    $this->Action = $Action;
    $this->UserNameElement = $UserNameElement;
    $this->PasswordElement = $PasswordElement;
    $this->Title = $Title;
    $this->Logo = $Logo;
  }
  
  // REQUIRED
  /**
   *
   * @var string
   */
  protected $Action;

  /**
   *
   * @var \Qck\Interfaces\Html\FormElement
   */
  protected $UserNameElement;

  /**
   *
   * @var \Qck\Html\Interfaces\FormElement
   */
  protected $PasswordElement;

  /**
   *
   * @var string
   */
  protected $Title;

  /**
   *
   * @var string
   */
  protected $Logo;

}
