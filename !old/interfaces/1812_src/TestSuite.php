<?php

namespace Qck\Interfaces;

/**
 * A interface for a test
 * @author muellerm
 */
interface TestSuite
{

  /**
   * @return string[] A set of Test Fqcns
   */
  function getTests();
}
