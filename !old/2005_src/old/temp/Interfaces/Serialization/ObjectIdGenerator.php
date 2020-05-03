<?php

namespace Qck\Interfaces\Serialization;

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
