<?php

namespace Qck\Sql\Interfaces;

/**
 * Represents a Column
 * 
 * @author muellerm
 */
interface Column extends \Qck\Sql\Convertable, \Qck\Interfaces\Expression
{

  function getName();
}
