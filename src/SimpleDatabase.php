<?php

namespace Qck;

/**
 * A class for exclusive writing to a File (other writing processes will wait until
 * another writing operation has finished)
 * 
 * @author muellerm
 */
class SimpleDatabase
{

    /**
     * 
     * @param \Qck\Interfaces\DataObject $Obj
     */
    function save( Interfaces\DataObject $Obj )
    {
        $this->ObjectsToSave[] = $Obj;
    }

    function commit()
    {
        if (count( $this->ObjectsToSave ) == 0)
            return;
        foreach ($this->ObjectsToSave as $Obj)
        {
            $Dir = $this->getClassDir( $Obj->getFqcn() );
            $File = $this->FileSystem->getPathFactory()->createPath($Dir, $FileBaseName);
        }
    }

    function __destruct()
    {
        $this->commit();
    }

    function relateTo( Interfaces\DataObject $LeftObj, Interfaces\DataObject $RightObj )
    {
        
    }

    function relateToMany( Interfaces\DataObject $LeftObj, array $RightObjects )
    {
        
    }

    function get( $Fqcn, callable $Matcher = null )
    {
        
    }

    function getFirst( $Fqcn, callable $Matcher = null )
    {
        
    }

    function getRelated( Interfaces\DataObject $Obj )
    {
        
    }

    function getFirstRelated( Interfaces\DataObject $Obj )
    {
        
    }

    protected function getClassDir( $Fqcn )
    {
        return $this->FileSystem->join( $this->DataDir, $Fqcn );
    }

    /**
     *
     * @var string
     */
    protected $DataDir;

    /**
     *
     * @var Interfaces\FileSystem 
     */
    protected $FileSystem;

    /**
     *
     * @var Interfaces\ArraySerializer 
     */
    protected $ArraySerializer;

    // -----------
    // state
    // -----------

    /**
     *
     * @var Interfaces\DataObject[] 
     */
    protected $ObjectsToSave = [];

}
