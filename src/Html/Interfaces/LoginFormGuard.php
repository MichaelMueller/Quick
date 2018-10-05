<?php

namespace Qck\Html\Interfaces;

/**
 * An interface for an object that can be rendered to HTML
 * 
 * @author muellerm
 */
interface LoginFormGuard
{

  /**
   * @return \Qck\Interfaces\Template
   */
  function getLoginForm( $Action, $UserNameElement, $PasswordElement, $Title, $Logo );
}
