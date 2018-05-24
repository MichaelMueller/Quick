<?php

namespace qck\GraphStorage;
/**
 *
 * @author muellerm
 */
interface Loader
{
  /**
   * 
   * @param string $Uuid
   * @return $Node
   */
  function load( $Uuid );
}
