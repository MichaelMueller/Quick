<?php

namespace Qck\Html\Bootstrap4;

/**
 * Description of HelloWorldPage
 *
 * @author muellerm
 */
class LoginForm implements \Qck\Interfaces\Template
{
  function __construct( Page $Page, $action, \Qck\Interfaces\Sql\Column $nameField,
                        \Qck\Interfaces\Sql\Column $passwordField )
  {
    $this->action = $action;
    $this->nameField = $nameField;
    $this->passwordField = $passwordField;
  }

  
  public function renderStyle()
  {
    ob_start();
    ?>
    <style>

      body
      {
        height: 100vh;
        width: 100%;
        overflow: visible;
      }
      #login_form_container
      {
        width: 50%;
        height: 50%;
        transform: translate(50%, 50%);
        background: rgb(49, 110, 163);
        border-radius: 5px;
        color: white;
        padding: 20px;
      }
    </style>
    <?php
    return ob_get_clean();
  }
  
  public function render()
  {
    ob_start();
    ?>

    <div id="login-form-container" class="jumbotron">
      <h1 class="display-4"><span class="fas fa-database">&nbsp;</span><?= $this->AppName ?></h1>
      <p class="lead">Please login.</p>
      <hr class="my-4">
      <form action="<?= $this->action ?>" method="POST">
        <div class="form-group">
          <label for="exampleInputEmail1">Email address</label>
          <input type="email" class="form-control" id="<?=$this->?>" placeholder="Enter email">
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">Password</label>
          <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
        </div>
        <button type="submit" class="btn btn-primary">Login <span class="fas fa-arrow-circle-right"></span></button>
      </form>
    </div>

    <?php
    return ob_get_clean();
  }

  /**
   *
   * @var string
   */
  protected $action;
  /**
   *
   * @var \Qck\Interfaces\Sql\Column
   */
  protected $nameField;
  /**
   *
   * @var \Qck\Interfaces\Sql\Column
   */
  protected $passwordField;
}
