<?php

namespace Qck\Expressions\Tests;

/**
 * implementation of a system cmd
 *
 * @author micha
 */
class Suite implements \Qck\Interfaces\TestSuite
{

  public function getTests()
  {
    return [ ExpressionsTest::class ];
  }
}
