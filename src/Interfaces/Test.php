<?php

namespace Qck\Interfaces;

/**
 * A interface for a test
 * @author muellerm
 */
interface Test extends ServicedFunctor
{
  /**
   * @return string[] A set of test Fqcns that must be run before
   */
  function getRequiredTests();
}
