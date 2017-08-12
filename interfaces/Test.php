<?php
namespace qck\interfaces;

/**
 *
 * Basic and simple interface for a controller
 * @author muellerm
 */
interface Test extends Controller
{  
  /**
   * @return array of FQCNs of test cases that need to be run before
   */
  public function getRequiredTests();
}
