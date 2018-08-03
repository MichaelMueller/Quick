<?php

namespace Qck\Sql\Tests;

/**
 * implementation of a system cmd
 *
 * @author micha
 */
class Suite implements \Qck\Interfaces\TestSuite
{

  public function getTests()
  {
    return [ SqlTest::class ];
  }
}
