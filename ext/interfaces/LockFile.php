<?php
namespace qck\ext\interfaces;
/**
 * Description of BasicGit
 *
 * @author muellerm
 */
interface LockFile
{  
  function lock();  
  function unlock();
  function isLocked();
}
