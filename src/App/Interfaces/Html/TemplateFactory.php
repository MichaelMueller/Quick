<?php

namespace Qck\App\Interfaces\Html;

/**
 * An interface for an object that can be rendered to HTML
 * 
 * @author muellerm
 */
interface TemplateFactory
{

  function createLoginForm( $Action, Template $UsernameInputField,
                            Template $PasswordInputField );
}
