<?php

namespace Qck\Interfaces\Html;

/**
 * An interface for an object that can be rendered to HTML
 * 
 * @author muellerm
 */
interface FormElement extends Snippet
{

  /**
   * @return string
   */
  function getLabel();

  /**
   * @return string
   */
  function getId();
}
