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
        $this->DataDir         = $DataDir;
        $this->ArraySerializer = $ArraySerializer;
        $this->Space           = $Space;
    }

    public function get( $Id )
    {
        return $this->loadFromFile( $this->getFilePath( $Id ) );
    }

    public function space( $SpaceName )
    {
        return new DataRegistry( $this->DataDir, $this->ArraySerializer, $this->getCurrentDir() . ($SpaceName ? "/" . $SpaceName : null) );
    }

    public function save( array $Data, $IdAttrName = "id" )
    {
        $Id = null;
        if ( $IdAttrName === null )
            $Id = $this->getNextId();
        else if ( !isset( $Data[ $IdAttrName ] ) )
        {
            $Id                  = $this->getNextId();
            $Data[ $IdAttrName ] = $Id;
        }
        else
            $Id = $Data[ $IdAttrName ];

        if ( !is_dir( $this->getCurrentDir() ) )
            mkdir( $this->getCurrentDir(), 0777, true );

        file_put_contents( $this->getFilePath( $Id ), $this->ArraySerializer->serialize( $Data ) );
        return $Id;
    }

    public function find( callable $Matcher = null, $FindFirst = false )
    {
        $Objects   = [];
        $GlobExt = $this->getCurrentDir() . '/*.' . $this->ArraySerializer->getFileExtension();
        $FilePaths = glob( $GlobExt );
        foreach ( $FilePaths as $FilePath )
        {
            $Object    = $this->loadFromFile( $FilePath );
            if ( $Object !== null && ($Matcher === null || $Matcher( $Object ) === true) )
                $Objects[] = $Object;
            if ( $FindFirst && count( $Objects ) == 1 )
                return $Objects[ 0 ];
        }
        return $Objects;
    }

    protected function getNextId()
    {
        $i = 0;
        do
            ++$i;
        while ( file_exists( $this->getFilePath( $i ) ) );
        return $i;
    }

    protected function getCurrentDir()
    {
        return $this->DataDir . ( $this->Space ? "/" . $this->Space : null );
    }

    protected function loadFromFile( $FilePath )
    {
        return file_exists( $FilePath ) ? $this->ArraySerializer->unserialize( file_get_contents( $FilePath ) ) : null;
    }

    protected function getFilePath( $Id )
    {
        return $this->getCurrentDir() . "/" . $Id . "." . $this->ArraySerializer->getFileExtension();
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
