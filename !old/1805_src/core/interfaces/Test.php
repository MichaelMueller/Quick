<?php

namespace qck\core\interfaces;

/**
 *
 * Basic and simple interface for a controller
 * @author muellerm
 */
interface Test
{

  /**
   * @return Response 
   */
  public function run( \qck\core\interfaces\AppConfig $config, array &$FilesToDelete = [] );

  /**
   * @return array of FQCNs of test cases that need to be run before
   */
  public function getRequiredTests();
}
