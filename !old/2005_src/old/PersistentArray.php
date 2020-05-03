<?php

namespace Qck;

/**
 * A class for exclusive writing to a File (other writing processes will wait until
 * another writing operation has finished)
 * 
 * @author muellerm
 */
class PersistentArray implements Interfaces\PeristentArray
{

    function __construct( $DataDir, Interfaces\ArraySerializer $ArraySerializer, $Id, $SanitizeKey = true )
    {
        $this->DataDir = $DataDir;
        $this->ArraySerializer = $ArraySerializer;
        $this->Id = $this->sanitizeKey( $Id );
        $this->SanitizeKey = $SanitizeKey;
    }

    public function create( $Id )
    {
        return new PersistentArray( $this->DataDir, $this->ArraySerializer, $Id, $this->SanitizeKey );
    }

    public function delete( $Key )
    {
        $this->assertDataLoaded();
        if (is_null( $Key ))
        {
            $this->Data = [];
            $this->Modified = true;
        }
        else if (isset( $this->Data[$Key] ))
        {
            unset( $this->Data[$Key] );
            $this->Modified = true;
        }
    }

    public function get( $Key )
    {
        $this->assertDataLoaded();
        return isset( $this->Data[$Key] ) ? $this->Data[$Key] : null;
    }

    public function has( $Key )
    {
        $this->assertDataLoaded();
        return isset( $this->Data[$Key] );
    }

    public function keys()
    {
        $this->assertDataLoaded();
        return array_keys( $this->Data );
    }

    public function set( $Key, $Value )
    {
        $this->assertDataLoaded();
        $this->sanitizeKey( $Key );
        $this->Data[$Key] = $Value;
        $this->Modified = true;
    }

    public function setValues( array $Values )
    {
        foreach ($Values as $Key => $Value)
            $this->set( $Key, $Value );
    }

    function save()
    {
        if ($this->Modified)
        {
            if (!is_dir( $this->getCurrentDir() ))
                mkdir( $this->getCurrentDir(), 0777, true );

            file_put_contents( $this->getDataFilePath(), $this->ArraySerializer->serialize( $this->Data ) );
            $this->Modified = false;
        }
    }

    function __destruct()
    {
        $this->save();
    }

    protected function sanitizeKey( $Key )
    {
        if ($this->SanitizeKey)
        {
            $Key = str_replace( "\\", "", $Key );
            $Key = str_replace( "/", "", $Key );
            return $Key;
        }
        else
        {
            if (strpos( $Key, "/" ) !== false || strpos( $Key, "\\" ))
                throw new InvalidArgumentException( "Path seperators \\ or / not allowed in a key." );
        }
    }

    protected function assertDataLoaded()
    {
        if (is_null( $this->Data ))
        {
            $FilePath = $this->getDataFilePath();
            $this->Data = file_exists( $FilePath ) ? $this->ArraySerializer->unserialize( file_get_contents( $FilePath ) ) : [];
        }
    }

    protected function getCurrentDir()
    {
        return $this->DataDir . "/" . implode( "/", $this->Id );
    }

    protected function getDataFilePath()
    {
        return $this->getCurrentDir() . "/" . $this->DataFileName . "." . $this->ArraySerializer->getFileExtension();
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
    protected $Id;

    /**
     *
     * @var string
     */
    protected $DataFileName;

    /**
     *
     * @var string
     */
    protected $SanitizeKey;

    /**
     *
     * @var array|null
     */
    protected $Data;

    /**
     *
     * @var bool 
     */
    protected $Modified = false;

}
