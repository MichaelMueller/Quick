<?php

namespace Qck\Apps\DatabaseApp\Templates;

/**
 * Description of HelloWorldPage
 *
 * @author muellerm
 */
class LoginForm implements \Qck\Interfaces\Template
{

  function __construct( $AppName, $Action )
  {
    $this->AppName = $AppName;
    $this->Action = $Action;
  }

  public function render()
  {
    ob_start();
    ?>

    <div class="container vhCenterContainer">
      <div class="row justify-content-center align-items-center h-100">
        <div class="col-12">
          <div class="jumbotron">
            <h1 class="display-4"><span class="fas fa-database">&nbsp;</span><?= $this->AppName ?></h1>
            <p class="lead">Please login.</p>
            <hr class="my-4">
            <form action="<?= $this->Action ?>" method="POST">
              <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
              </div>
              <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
              </div>
              <button type="submit" class="btn btn-primary">Login <span class="fas fa-arrow-circle-right"></span></button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <?php
    return ob_get_clean();
  }

  protected $AppName;
  protected $Action;

}
