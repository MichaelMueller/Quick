<?php
namespace qck\interfaces;

/**
 *
 * Basic and simple interface for a controller
 * @author muellerm
 */
interface SetupController extends Controller
{  
  /**
   * @return bool 
   */
  public function isUpdateNecessary();
}
