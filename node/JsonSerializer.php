<?php

namespace qck\node;

/**
 *
 * @author muellerm
 */
class JsonSerializer extends abstracts\Serializer
{

  public function getFileExtensionWithoutDot()
  {
    return "json";
  }

  protected function serializeArray( $Array )
  {
    return json_encode( $Array, JSON_PRETTY_PRINT );
  }

  protected function unserializeToArray( $String )
  {
    return json_decode( $String, true );
  }
}
