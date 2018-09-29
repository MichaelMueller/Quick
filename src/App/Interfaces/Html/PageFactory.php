<?php

namespace Qck\Html\Interfaces;

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
   * @return Page
   */
  function create( $Title );
}
