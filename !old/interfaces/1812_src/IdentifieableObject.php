<?php

namespace Qck\Interfaces;

/**
 * An object that has properties to describe itself.
 * 
 * @author muellerm
 */
interface IdentifieableObject
{

  /**
   * @return string
   */
  function getName();

  /**
   * @return string a base64 encoded string or null if not set
   */
  function getPicture();

  /**
   * @return string
   */
  function getDescription();
}
