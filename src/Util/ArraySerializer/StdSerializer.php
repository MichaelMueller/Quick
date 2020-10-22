<?php

namespace Qck\Util\ArraySerializer;

/**
 * Description of PhpSerializer
 *
 * @author muellerm
 */
class StdSerializer implements \Qck\Interfaces\ArraySerializer
{

  public function fileExtension()
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