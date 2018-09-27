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
   * @param type $FilePath
   */
  function addCallback( callable $Callback );

  /**
   * Adds a Functor to be run later
   * @param Functor $Functor
   */
  function addFunctor( Functor $Functor );

  /**
   * run all cleaning tasks now
   */
  function tidyUp();
}
