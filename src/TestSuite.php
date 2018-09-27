<?php

namespace Qck;

/**
 * Description of ServiceRepo
 *
 * @author muellerm
 */
class TestSuite implements \Qck\Interfaces\TestSuite
{

  public function getTests()
  {
    return [ Tests\FileSystem::class ];
  }
}
