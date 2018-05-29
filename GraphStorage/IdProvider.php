<?php

namespace qck\GraphStorage;

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
  
  function getFqcn();
}
