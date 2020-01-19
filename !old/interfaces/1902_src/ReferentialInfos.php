<?php

namespace Qck\Interfaces;

/**
 * An interface for conversion of objects to an array of scalar. Implementing classes must "know" these
 * classes.
 *  
 * @author muellerm
 */
interface ReferentialInfos
{

  /**
   * @return ReferentialInfo
   */
  function getReferentialInfo( $Fqcn );
}
