<?php

namespace Qck\App\Interfaces\Html;

/**
 * An interface for an object that can be rendered to HTML
 * 
 * @author muellerm
 */
interface PageFactory
{

  /**
   * 
   * @param string $Title
   * @param mixed $BodyTemplateOrText
   * @return Page
   */
  function create( $Title, $BodyTemplateOrText,
                   \Qck\App\Interfaces\LanguageProvider $LanguageProvider = null );
}
