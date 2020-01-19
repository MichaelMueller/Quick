<?php

namespace Qck\Interfaces;

/**
 * An Config Service Class
 * 
 * @author muellerm
 */
interface LocalDataDirProvider
{

  /**
   * @return string
   */
  function getLocalDataDir( $createIfNotExists = true );

}
