<?php

namespace Qck\Tests;

/**
 * Description of ServiceRepo
 *
 * @author muellerm
 */
class Suite implements \Qck\Interfaces\TestSuite
{

  public function getTests()
  {
    return [ FileSystem::class, Cleaner::class ];
  }
}
