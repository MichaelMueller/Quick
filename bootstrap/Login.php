<?php

namespace qck\bootstrap;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class Login implements \qck\interfaces\Template
{

  function setFormActionUrl( $FormActionUrl )
  {
    $this->FormActionUrl = $FormActionUrl;
  }

  public function render()
  {
    ob_start();
    ?>
    <div class="container" style="height: 100vh;">
      <div style="background-color: #eaeaea; 
           border-radius: 5px; padding: 5vw;
           position: relative;
           top: 50%;
           transform: translateY(-50%);">
        <h1>Login</h1>
        <form action="<?= $this->FormActionUrl ?>" method="POST">
          <div class="form-group">
            <label for="Email">Email address</label>
            <input type="email" class="form-control" id="Email" aria-describedby="emailHelp" placeholder="Enter email">
          </div>
          <div class="form-group">
            <label for="Password">Password</label>
            <input type="password" class="form-control" id="Password" placeholder="Password">
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
    <?php
    return ob_get_clean();
  }

  private $FormActionUrl;

}
