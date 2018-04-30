<?php

namespace qck\db;

/**
 *
 * @author muellerm
 */
class JsonSerializer implements interfaces\ArraySerializer
{

  public function fromString( $String )
  {
    return json_decode( $String, true );
  }

  public function toString( array $Array )
  {
    return json_encode( $Array, JSON_PRETTY_PRINT );
  }

  public function getFileExtensionWithoutDot()
  {
    return "json";
  }
}
