<?php

namespace Qck\Interfaces;

/**
 * A central interface for addressing objects using an id
 * @author muellerm
 */
interface ObjectIdGenerator
{

  /**
   * @return mixed An Id
   */
  function generateNextId();
}
