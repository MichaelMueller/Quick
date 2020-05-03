<?php

namespace Qck\Interfaces\Html;

/**
 * An interface for an object that can be rendered to HTML
 * 
 * @author muellerm
 */
interface LoginFormBroker
{

  /**
   * @return \Qck\Interfaces\Template
   */
  function getLoginForm( $Action, $UsernameElement, $PasswordElement, $Title, $Logo );
}
