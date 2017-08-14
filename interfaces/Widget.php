<?php
namespace qck\interfaces;

/**
 *
 * @author muellerm
 */
interface Widget extends \qck\interfaces\Template
{
  /**
   * @return int a minimum width for this widget
   */
  function getMinWidth();
}
