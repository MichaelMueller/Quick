<?php

namespace Qck;

/**
 * Description of HelloWorldController
 *
 * @author muellerm
 */
class FileDataNode implements \Qck\Interfaces\DataNode
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
        return new FileDataNode( $this->DataDir, $this->ArraySerializer, $Id );
    }

    function __get( $Key )
    {
        return $this->get( $Key );
    }

    function __set( $Key, $Name )
    {
        return $this->get( $Key );
    }

    public function get( $Key, callable $Default = null )
    {
        $this->assertLoaded();
        if (!isset( $this->Data[$Key] ))
        {
            if ($Default !== null)
                $this->set( $Key, call_user_func( $Default ) );
            else
                return null;
        }

        if (is_array( $this->Data[$Key] ))
            $this->Data[$Key] = $this->create( $this->Data[$Key][0] );

        return $this->Data[$Key];
    }

    public function getData()
    {
        $Data = [];
        foreach ($this->keys() as $key)
            $Data[$key] = $this->get( $Key );
        return $Data;
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
            /* @var $DataNode Interfaces\DataNode */
            $DataNode = null;
            if ($Value instanceof Interfaces\DataNodeProvider)
                $DataNode = $Value->getDataNode();
            else if ($Value instanceof Interfaces\DataNode)
                $DataNode = $Value;

            if ($DataNode !== null)
            {
                $DataNode->save();
                $Value = [$DataNode->getId()];
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
        if (file_exists( $FilePath )
                && filesize( $FilePath )
                > 0)
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
        } while (file_exists( $this->FilePath ));
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
