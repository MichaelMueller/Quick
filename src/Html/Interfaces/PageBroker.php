<?php

namespace Qck\Html\Interfaces;

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
   * @return \Qck\Html\Interfaces\Page
   */
  function getPage( $BodyTemplateOrText );
}
