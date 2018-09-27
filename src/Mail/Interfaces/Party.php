<?php

namespace Qck\Mail\Interfaces;

/**
 * Class representing a "party" in Mail exchange - meaning a person, contact or just the combination of Name and Address
 * @author muellerm
 */
interface Party
{

  /**
   * @return string
   */
  function getName();

  /**
   * @return string
   */
  function getMailAddress();
}
