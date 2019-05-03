<?php

namespace Qck;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class FileActiveRecord implements \Qck\Interfaces\ActiveRecord
{

    function __construct( $DataDir, Interfaces\ArraySerializer $ArraySerializer, $Id = null )
    {
        $this->DataDir = $DataDir;
        $this->ArraySerializer = $ArraySerializer;
        $this->Id = $Id;
    }

    function getId()
    {
        return $this->Id;
    }

    public function set( $Key, $Value )
    {
        $this->assertLoaded();
        $this->Data[$Key] = $Value;
        $this->Modified = true;
    }

    public function has( $Key )
    {
        $this->assertLoaded();
        return isset( $this->Data[$Key] );
    }

    public function add( $Value )
    {
        $this->assertLoaded();
        $this->Data[] = $Value;
        $this->Modified = true;
    }

    public function create( $Id = null )
    {
        return new FileActiveRecord( $this->DataDir, $this->ArraySerializer, $Id );
    }

    public function get( $Key, $Default = null )
    {
        $this->assertLoaded();
        if (!isset( $this->Data[$Key] ))
        {
            $this->Data[$Key] = $Default;
            $this->Modified = true;
        }
        else if (is_scalar( $this->Data[$Key] ))
        {
            $Value = null;
            if ($Default instanceof Interfaces\ActiveRecordConsumer)
            {
                $Default->setActiveRecord( $this->create( $this->Data[$Key] ) );
                $Value = $Default;
            }
            else if ($Default instanceof Interfaces\ActiveRecord)
            {
                $Default->setId( $this->Data[$Key] );
                $Value = $Default;
            }
            if ($Value)
            {
                $this->Data[$Key] = $Value;
                $this->Modified = true;
            }
        }
        return $this->Data[$Key];
    }

    public function getData()
    {
        $this->assertLoaded();
        return $this->Data;
    }

    public function values()
    {
        $this->assertLoaded();
        return array_values( $this->Data );
    }

    public function keys()
    {
        $this->assertLoaded();
        return array_keys( $this->Data );
    }

    public function remove( $Key )
    {
        $this->assertLoaded();
        unset( $this->Data[$Key] );
        $this->Modified = true;
    }

    public function save()
    {
        if ($this->Modified == false)
            return;

        $ScalarData = [];
        foreach ($this->Data as $Key => $Value)
        {
            /* @var $ActiveRecord Interfaces\ActiveRecord */
            $ActiveRecord = null;
            if ($Value instanceof Interfaces\ActiveRecordProvider)
                $ActiveRecord = $Value->getActiveRecord();
            else if ($Value instanceof Interfaces\ActiveRecord)
                $ActiveRecord = $Value;
            if ($ActiveRecord !== null)
            {
                $ActiveRecord->save();
                $Value = $ActiveRecord->getId();
            }

            $ScalarData[$Key] = $Value;
        }

        $this->assertIdCreated();
        $FilePath = $this->getFilePath();
        file_put_contents( $FilePath, $this->ArraySerializer->serialize( $ScalarData ), LOCK_EX );
        $this->Modified = false;
    }

    protected function assertLoaded()
    {
        if ($this->Loaded)
            return;

        $FilePath = $this->getFilePath();
        if (file_exists( $FilePath ) && filesize( $FilePath ) > 0)
            $this->Data = $this->ArraySerializer->unserialize( file_get_contents( $FilePath ) );

        $this->Loaded = true;
    }

    protected function getFilePath()
    {
        return $this->DataDir . "/" . $this->Id . "." . $this->ArraySerializer->getFileExtension();
    }

    protected function assertIdCreated()
    {
        if (!is_null( $this->Id ))
            return;

        do
        {
            ++$this->Id;
            $this->FilePath = $this->createFilePath();
        }
        while (file_exists( $this->FilePath ));
        touch( $this->FilePath );
    }

    public function setId( $Id )
    {
        $this->Id = $Id;
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
     * @var mixed
     */
    protected $Id;

    /**
     *
     * @var array 
     */
    protected $Data = [];

    /**
     *
     * @var bool 
     */
    protected $Modified = false;

    /**
     *
     * @var bool 
     */
    protected $Loaded = false;

}
