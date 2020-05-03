<?php

namespace Qck\Interfaces;

/**
 * Abstraction for a class that generates File objects based on an id and fileextensions
 *  
 * @author muellerm
 */
interface DataDirectory
{

  /**
   * 
   * @param mixed $Id
   * @return \Qck\Interfaces\Path
   */
  function getFile( $Id );
}
