<?php

namespace Qck\Expression;

/**
 * Description of ServiceRepo
 *
 * @author muellerm
 */
class TestSuite implements \Qck\Interfaces\TestSuite
{

  public function getTests()
  {
    return [ Tests\ExpressionTest::class ];
  }
}
