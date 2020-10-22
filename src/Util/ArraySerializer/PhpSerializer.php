<?php

namespace Qck\Util\ArraySerializer;

/**
 * Description of PhpSerializer
 *
 * @author muellerm
 */
class PhpSerializer implements \Qck\Interfaces\ArraySerializer
{

    public function fileExtension()
    {
        return "php";
    }

    public function serialize( array $arrayOfScalars )
    {
        return var_export( $arrayOfScalars, true );
    }

    public function unserialize( $dataString )
    {
        eval( '$data = ' . $dataString . ';' );
        return $data;
    }


}
