<?php

namespace Qck\Interfaces\Serialization;

/**
 * An interface for conversion of objects to an array of scalar. Implementing classes must "know" these
 * classes.
 *  
 * @author muellerm
 */
interface DataFileProvider
{

  /**
   * 
   * @param mixed $Id
   * @param string $FileExtension
   * @return \Qck\Interfaces\Path
   */
  function getFile( $Id );

  /**
   * 
   * @return \Qck\Interfaces\ArraySerializer
   */
  function getArraySerializer();
}
