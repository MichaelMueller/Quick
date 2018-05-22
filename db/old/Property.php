<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
abstract class Property
{
  abstract function update( SqlDb $SqlDb, Property $PreviousProperty = null );
  protected $Name;

}
