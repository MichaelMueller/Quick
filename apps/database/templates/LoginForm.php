<?php

namespace qck\apps\database\templates;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class LoginForm implements \qck\interfaces\Template
{

  public function render()
  {
    ob_start();
    ?>

    <div class="centerLayout">
      
      <div class="dialogBar"><?= $this->Config->getAppName() ?> - Login</div>
      
      <form class="dialogBody" action="<?= $this->Config->createLink( "Login" ) ?>" method="POST">

        <input type="email" id="inputEmail" class="form-control velem" placeholder="Email address" required autofocus>
        <input type="password" id="inputPassword" class="form-control velem" placeholder="Password" required>
        <label class="velem"><input type="checkbox" value="remember-me">Remember me</label>
        <button type="submit" class="velem btn">Sign in</button>

      </form>
      
    </div>
    <?php
    return ob_get_clean();
  }

  function setConfig( \qck\interfaces\AppBaseConfig $Config )
  {
    $this->Config = $Config;
  }

  /**
   *
   * @var \qck\interfaces\AppBaseConfig
   */
  private $Config;

}
