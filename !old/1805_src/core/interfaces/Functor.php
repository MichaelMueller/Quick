<?php

namespace qck\core\interfaces;

/**
 *
 * Generic interface for an object oriented encapsulation of a function
 * @author muellerm
 */
interface Functor
{

  /**
   * @return Response 
   */
  public function run();
}
