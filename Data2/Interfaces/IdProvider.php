<?php

namespace qck\Data2\Interfaces;

/**
 *
 * @author muellerm
 */
interface IdProvider
{
  /**
   * @return string A Id (if none is
   */
  function getId();
  /**
   * @return string of the object
   */
  function getFqcn();
}
