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
   * @return int the size
   */
  function add( interfaces\BackupTask $BackupTask );
  
  function run();
}
