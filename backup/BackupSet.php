<?php

namespace qck\backup;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class BackupSet implements interfaces\BackupSet
{

  function add( interfaces\BackupTask $BackupTask )
  {
    $this->Tasks[] = $BackupTask;
  }

  /**
   * 
   * @param int $index
   * @return interfaces\BackupTask
   */
  function at( $index )
  {
    return $this->Tasks[ $index ];
  }

  function size()
  {
    return count( $this->Tasks );
  }

  protected $Tasks = [];

}
