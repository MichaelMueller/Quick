<?php

namespace qck\Data;

/**
 *
 * @author muellerm
 */
class ModifiedTimeProperty extends StringProperty
{

  public function __construct()
  {
    parent::__construct( "ModifiedTime", 0, 255 );
  }
}
