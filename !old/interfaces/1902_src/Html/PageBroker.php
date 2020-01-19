<?php

namespace Qck\Interfaces\Html;

/**
 * An interface for an object that can be rendered to HTML
 * 
 * @author muellerm
 */
interface PageBroker
{

  /**
   * 
   * @param string $Title
   * @param mixed $BodyTemplateOrText
   * @return \Qck\Interfaces\Html\Page
   */
  function getPage( $BodyTemplateOrText );
}
