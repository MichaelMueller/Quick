<?php

namespace Qck\Serializer;

/**
 * Description of PhpSerializer
 *
 * @author muellerm
 */
class StdSerializer implements \Qck\Interfaces\ArraySerializer
{

  public function getFileExtension()
  {
    return "data";
  }

  public function serialize( array $ArrayOfScalars )
  {
    return serialize( $ArrayOfScalars );
  }

  public function unserialize( $DataString )
  {
    return unserialize( $DataString );
  }
}
