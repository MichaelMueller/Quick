<?php

namespace Qck\Interfaces;

/**
 * A Cleaner Service to do things later
 * @author muellerm
 */
interface Cleaner
{

  /**
   * Adds a file or directory to be deleted later
   * @param string $FilePath
   */
  function addFile( $FilePath );

  /**
   * Adds a callback to be run later
   * @param callable $Callback
   */
  function addCallback( callable $Callback );

  /**
   * run all cleaning tasks now
   */
  function tidyUp();
}
