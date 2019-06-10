<?php

namespace Qck\Serializer;

/**
 * Description of PhpSerializer
 *
 * @author muellerm
 */
class CsvSerializer implements \Qck\Interfaces\ArraySerializer
{

    public function getFileExtension()
    {
        return "php";
    }

    public function serialize( array $ArrayOfScalars )
    {
        return var_export( $ArrayOfScalars, true );
    }

    public function unserialize( $DataString )
    {
        eval( '$Data = ' . $DataString . ';' );
        return $Data;
    }


}
