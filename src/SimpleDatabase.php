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
        if (!in_array( $Obj, $this->ObjectsToSave, true ))
            $this->ObjectsToSave[] = $Obj;
    }

    function saveArrayOfObjects( array $Objs )
    {
        foreach ($Objs as $OtherObj)
            $this->save( $OtherObj );
    }

    function saveMany( Interfaces\DataObject $Obj, ... $Objs )
    {
        $this->save( $Obj );
        foreach ($Objs as $OtherObj)
            is_array( $OtherObj ) ? $this->saveArrayOfObjects( $Objs ) : $this->save( $OtherObj );
    }

    function relateTo( Interfaces\DataObject $LeftObj, Interfaces\DataObject $RightObj )
    {
        $this->saveMany( $LeftObj, $RightObj );
        $Id = spl_object_id( $LeftObj );
        if (!isset( $this->Relations[$Id] ))
            $this->Relations[$Id] = [$LeftObj];
        $this->Relations[$Id][] = $RightObj;
    }

    function relateToMany( Interfaces\DataObject $LeftObj, array $RightObjects )
    {
        foreach ($RightObjects as $RightObj)
            $this->relateTo( $LeftObj, $RightObj );
    }

    function commit()
    {
        if (count( $this->ObjectsToSave ) == 0)
            return;
        // save objects
        while (count( $this->ObjectsToSave ) > 0)
        {
            $Obj = array_shift( $this->ObjectsToSave );
            $FilePath = $this->getFilePath( $Obj );
            file_put_contents( $FilePath, $this->ArraySerializer->serialize( $Obj->toArray() ) );
        }

        // save relations
        while (count( $this->Relations ) > 0)
        {
            $RelationSet = array_shift( $this->Relations );
            $LeftObj = array_shift( $this->Relations );
            $FilePath = $this->getFilePath( $Obj, true );
            $Relations = file_exists( $FilePath ) ? $this->ArraySerializer->unserialize( file_get_contents( $FilePath ) ) : [];
            while (count( $RelationSet ) > 0)
            {
                $RightObj = array_shift( $this->Relations );
                $RightObjFqcn = $RightObj->getFqcn();
                if (!isset( $Relations[$RightObjFqcn] ))
                    $Relations[$RightObjFqcn] = [];
                if (!\in_array( $RightObj->getId(), $Relations ))
                    $Relations[$RightObjFqcn][] = $RightObj;
            }
            file_put_contents( $FilePath, $this->ArraySerializer->serialize( $Relations ) );
        }
    }

    function __destruct()
    {
        $this->commit();
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
        return $this->DataDir . "/" . $Fqcn;
    }

    protected function getFilePathForId( $Dir, $Id, $RelationsFile = false )
    {
        return $Dir . "/" . $Id . $RelationsFile ? ".relations " : "" . "." . $this->ArraySerializer->getFileExtension();
    }

    protected function getFilePath( Interfaces\DataObject $Object, $RelationsFile = false )
    {
        $Dir = $this->getClassDir( $Object->getFqcn() );

        $FilePath = $this->getFilePathForId( $Dir, $Object->getId(), $RelationsFile );
        if (!file_exists( $FilePath ))
        {
            $Id = 0;
            do
            {
                ++$Id;
                $FilePath = $this->getFilePathForId( $Dir, $Id, $RelationsFile );
            } while (file_exists( $FilePath ));
            $Object->setId( $Id );
            if (!is_dir( $Dir ))
                mkdir( $Dir, 0777, true );
            touch( $FilePath );
        }

        return $FilePath;
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

// -----------
// state
// -----------

    /**
     *
     * @var Interfaces\DataObject[] 
     */
    protected $ObjectsToSave = [];

    /**
     *
     * @var array 
     */
    protected $Relations = [];

}
