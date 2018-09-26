<?php

namespace Qck\Interfaces\Mail;

/**
 * Class representing an actual mail message
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
