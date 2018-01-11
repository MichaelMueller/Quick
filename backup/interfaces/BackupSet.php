<?php

namespace qck\backup\interfaces;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
interface BackupSet
{

  /**
   * 
   * @param int $index
   * @return BackupTask
   */
  function at( $index );

  /**
   * @return int the size
   */
  function size();
}
