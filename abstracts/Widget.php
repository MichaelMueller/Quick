<?php

namespace qck\abstracts;

/**
 * a Controller that only runs for logged in users
 *
 * @author muellerm
 */
abstract class Widget implements \qck\interfaces\Widget
{
  function getMinWidth()
  {
    return 319;
  }
}
