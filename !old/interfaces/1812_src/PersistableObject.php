<?php

namespace Qck\Interfaces;

/**
 *
 * Basic and simple interface for a DataNode
 * @author muellerm
 */
interface PersistableObject
{

  /**
   * 
   */
  function setObjectStorage( ObjectStorage $ObjectStorage );

  /**
   * @return ObjectStorage
   */
  function getObjectStorage();
}
