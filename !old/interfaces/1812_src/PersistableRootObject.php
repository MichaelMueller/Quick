<?php

namespace Qck\Interfaces;

/**
 *
 * Basic and simple interface for a DataNode
 * @author muellerm
 */
interface PersistableRootObject extends PersistableObject
{

  /**
   * @return void
   */
  function getId();
}
