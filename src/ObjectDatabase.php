<?php

namespace Qck;

/**
 * A class for exclusive writing to a File (other writing processes will wait until
 * another writing operation has finished)
 * 
 * @author muellerm
 */
class ObjectDatabase
{

    function get( $Fqcn, $Id )
    {
        $Data = $this->DataRegistry->space( $Fqcn )->get( $Id );
        return $this->createObject( $Fqcn, $Data );
    }

    function find( $Fqcn, callable $Matcher = null, $FindFirst = false )
    {
        $DataSet   = $this->DataRegistry->space( $Fqcn )->find( $Matcher, $FindFirst );
        $Objects   = [];
        foreach ( $DataSet as $Data )
            $Objects[] = $this->createObject( $Fqcn, $Data );
        return $Objects;
    }

    protected function createObject( $Fqcn, $Data )
    {
        /* @var $Obj Interfaces\DataObject */
        $Obj = new $Fqcn();
        $Obj->fromArray( $Data );
        return $Obj;
    }

    function getRelated( Interfaces\DataObject $LeftObj, $RightFqcn, $FindFirst = false )
    {
        $RelatedIds = $this->DataRegistry->space( "relations" )->space( $LeftObj->getFqcn() )->get( $LeftObj->getId() );
        $Objects   = [];
        foreach ( $DataSet as $Data )
            $Objects[] = $this->createObject( $Fqcn, $Data );
        return $Objects;
    }

    /**
     * 
     * @param \Qck\Interfaces\DataObject $Obj
     */
    function save( Interfaces\DataObject $Obj )
    {
        if ( !in_array( $Obj, $this->ObjectsToSave, true ) )
            $this->ObjectsToSave[] = $Obj;
    }

    function saveMany( ... $ObjectsOrArrayOfObjects )
    {
        foreach ( $ObjectsOrArrayOfObjects as $Element )
            is_array( $Element ) ? call_user_func_array( array ( $this, "saveMany" ), $Element ) : $this->save( $Element );
    }

    function delete( $Fqcn, $Id )
    {
        $this->addToSubArray( $this->ObjectsToDelete, $Fqcn, $Id );
    }

    function relateTo( Interfaces\DataObject $LeftObj, Interfaces\DataObject $RightObj )
    {
        $this->saveMany( $LeftObj, $RightObj );
        $this->addToSubArray( $this->RelationsToSave, spl_object_id( $LeftObj ), $RightObj, [ $LeftObj ] );
    }

    function relateToMany( Interfaces\DataObject $LeftObj, array $RightObjects )
    {
        foreach ( $RightObjects as $RightObj )
            $this->relateTo( $LeftObj, $RightObj );
    }

    function commit()
    {
        // save objects
        foreach ( $this->ObjectsToSave as $Idx => $Object )
        {
            $FilePath = $this->getFilePath( $Object );
            file_put_contents( $FilePath, $this->ArraySerializer->serialize( $Object->toArray() ) );
            unset( $this->ObjectsToSave[ $Idx ] );
        }

        $this->commitDeletions();
        $this->commitRelations();
    }

    protected function commitDeletions()
    {

        foreach ( $this->ObjectsToDelete as $Fqcn => $Ids )
        {
            $Dir = $this->getClassDir( $Fqcn );
            foreach ( $Ids as $Id )
            {
                $DataFile          = $this->getFilePathForId( $Dir, $Id );
                if ( file_exists( $DataFile ) )
                    unlink( $DataFile );
                $RelationsDataFile = $this->getFilePathForId( $Dir, $Id, true );
                if ( file_exists( $RelationsDataFile ) )
                    unlink( $RelationsDataFile );
                unset( $this->ObjectsToDelete[ $Idx ] );
            }
        }
    }

    function __destruct()
    {
        $this->commit();
    }

    protected function commitRelations()
    {
        // save relations
        foreach ( $this->RelationsToSave as $ObjectId => $RightObjects )
        {
            $LeftObject        = array_shift( $RightObjects );
            $RelationsFilePath = null;
            $RelatedIdsPerFqcn = $this->getRelations( $LeftObject->getFqcn(), $LeftObject->getId(), $RelationsFilePath );
            foreach ( $RightObjects as $RightObject )
            {
                $Fqcn = $RightObject->getFqcn();
                $Id   = $RightObject->getId();
                $this->addToSubArray( $RelatedIdsPerFqcn, $Fqcn, $Id );
            }
            file_put_contents( $RelationsFilePath, $this->ArraySerializer->serialize( $RelatedIdsPerFqcn ) );
            unset( $this->RelationsToSave[ $ObjectId ] );
        }
    }

    protected function addToSubArray( &$Array, $ParentKey, $Val, $InitialArray = [] )
    {
        if ( !isset( $Array[ $ParentKey ] ) )
            $Array[ $ParentKey ]   = $InitialArray;
        if ( !in_array( $Val, $Array[ $ParentKey ] ) )
            $Array[ $ParentKey ][] = $Val;
    }

    protected function getRelations( $Fqcn, $Id, &$RelationsFilePath )
    {
        $RelationsFilePath = $this->getFilePathForId( $this->getClassDir( $Fqcn ), $Id, true );
        return file_exists( $RelationsFilePath ) ? $this->ArraySerializer->unserialize( file_get_contents( $RelationsFilePath ) ) : [];
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
        if ( !file_exists( $FilePath ) )
        {
            $Id = 0;
            do
            {
                ++$Id;
                $FilePath = $this->getFilePathForId( $Dir, $Id, $RelationsFile );
            }
            while ( file_exists( $FilePath ) );
            $Object->setId( $Id );
            if ( !is_dir( $Dir ) )
                mkdir( $Dir, 0777, true );
            touch( $FilePath );
        }

        return $FilePath;
    }

    /**
     *
     * @var DataRegistry
     */
    protected $DataRegistry;

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
     * @var Interfaces\DataObject[] 
     */
    protected $ObjectsToDelete = [];

    /**
     *
     * @var array 
     */
    protected $RelationsToSave = [];

}
