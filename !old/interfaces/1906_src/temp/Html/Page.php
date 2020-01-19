<?php

namespace Qck\Interfaces\Html;

/**
 * An interface for an object that can be rendered to HTML
 * 
 * @author muellerm
 */
interface Page extends \Qck\Interfaces\Output, Snippet
{

  /**
   * 
   * @param string $href
   * @param string $integrity
   * @param string $crossOrigin
   */
  function addCssLink( $href, $integrity = null, $crossOrigin = null );

  /**
   * 
   * @param string $src
   * @param string $integrity
   * @param string $crossOrigin
   * @param bool $PlaceBeforeBodyEndTag
   */
  function addJavaScript( $src, $integrity = null, $crossOrigin = null,
                          $PlaceBeforeBodyEndTag = true );

  /**
   * 
   * @param string $Title
   */
  function setTitle($Title);
  
  /**
   * sets a language provider
   * @param \Qck\Interfaces\LanguageProvider $LanguageProvider
   */
  function setLanguageProvider( \Qck\Interfaces\LanguageProvider $LanguageProvider );
}
