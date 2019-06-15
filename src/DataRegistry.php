<?php

namespace Qck;

/**
 * A class for exclusive writing to a File (other writing processes will wait until
 * another writing operation has finished)
 * 
 * @author muellerm
 */
class DataRegistry implements Interfaces\DataRegistry
{

    function __construct( $DataDir, Interfaces\ArraySerializer $ArraySerializer, $Space = null )
    {
        $this->DataDir = $DataDir;
        $this->ArraySerializer = $ArraySerializer;
        $this->Space = $Space;
    }

    public function get( $Key )
    {
        return $this->loadFromFile( $this->getFilePath( $Key ) );
    }

    public function getSpace( $SpaceName )
    {
        return new DataRegistry( $this->DataDir, $this->ArraySerializer, $this->Space . $SpaceName );
    }

    public function save( array $Data, $KeyAttrName = "id" )
    {
        $Key = null;
        if ($KeyAttrName === null)
            $Key = $this->getNextKey();
        else if (!isset( $Data[$KeyAttrName] ))
        {
            $Key = $this->getNextKey();
            $Data[$KeyAttrName] = $Key;
        }
        else
            $Key = $Data[$KeyAttrName];

        if (!is_dir( $this->DataDir ))
            mkdir( $this->DataDir, 0777, true );
        file_put_contents( $this->getFilePath( $Key ), $this->ArraySerializer->serialize( $Data ) );
    }

    public function find( callable $Matcher = null, $FindFirst = false )
    {
        $Objects = [];
        foreach (glob( $this->DataDir . '/*.' + $this->ArraySerializer->getFileExtension() ) as $FilePath)
        {
            $Object = $this->loadFromFile( $FilePath );
            if ($Object !== null && ($Matcher === null || $Matcher( $Object ) === true))
                $Objects[] = $Object;
            if ($FindFirst && count( $Objects ) == 1)
                return $Objects[0];
        }
        return $Objects;
    }

    protected function getNextKey()
    {
        $i = 0;
        do
            ++$i; while (file_exists( $this->getFilePath( $i ) ));
        return $i;
    }

    protected function get64BitHash( $str )
    {
        return gmp_strval( gmp_init( substr( md5( $str ), 0, 16 ), 16 ), 10 );
    }

    protected function loadFromFile( $FilePath )
    {
        return file_exists( $FilePath ) ? $this->ArraySerializer->unserialize( file_get_contents( $FilePath ) ) : null;
    }

    protected function getFilePath( $Key )
    {
        return $this->DataDir . "/" . $this->get64BitHash( $this->Space . $Key ) . "." . $this->ArraySerializer->getFileExtension();
    }

    /**
     *
     * @var string
     */
    protected $DataDir;

    /**
     *
     * @var Interfaces\ArraySerializer 
     */
    protected $ArraySerializer;

    /**
     *
     * @var string
     */
    protected $Space;

}
