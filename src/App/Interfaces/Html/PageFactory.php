<?php

namespace Qck\App\Interfaces\Html;

/**
 * An interface for an object that can be rendered to HTML
 * 
 * @author muellerm
 */
interface PageGuard
{

  /**
   * 
   * @param string $Title
   * @param mixed $BodyTemplateOrText
   * @return Page
   */
  function getPage( $Title, $BodyTemplateOrText );
}
