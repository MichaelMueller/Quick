<?php

namespace Qck\Interfaces;

/**
 * A interface for a test
 * @author muellerm
 */
interface Test
{
/**
 * 
 * @param \Qck\Interfaces\FileSystem $FileSystem
 * @param \Qck\Interfaces\Cleaner $Cleaner
 */
  function run( FileSystem $FileSystem, Cleaner $Cleaner );

  /**
   * @return string[] A set of test Fqcns that must be run before
   */
  function getRequiredTests();
}
