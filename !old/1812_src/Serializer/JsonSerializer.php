<?php

namespace Qck\Serializer;

/**
 *
 * @author muellerm
 */
class JsonSerializer implements \Qck\Interfaces\Serializer
{

  public function getFileExtension()
  {
    return "json";
  }

  public function serialize( array $Array )
  {
    return json_encode( $Array, JSON_PRETTY_PRINT );
  }

  public function unserialize( $DataString )
  {
    return json_decode( $DataString, true );
  }
}
