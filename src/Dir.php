<?php

namespace Qck;

/**
 *
 * @author muellerm
 */
class Dir implements Interfaces\Dir
{

    function __construct( string $Value, int $DirMask = 0755 )
    {
        $this->Value   = $Value;
        $this->DirMask = $DirMask;
    }

    public function get( $AssertCreated = true )
    {
        static $Dir = null;
        if ( is_null( $Dir ) )
            $Dir        = $this->getFileOrDirPath( $this->Value, false, $AssertCreated );
        return $Dir;
    }

    public function getFilePath( $RelativeFilePath, $AssertCreated = true )
    {
        return $this->getFileOrDirPath( $this->Value . "/" . $RelativeFilePath, true, $AssertCreated );
    }

    public function getSubDir( $SubDir, $AssertCreated = true )
    {
        return $this->getFileOrDirPath( $this->Value . "/" . $SubDir, false, $AssertCreated );
    }

    protected function getFileOrDirPath( $Path, $IsFile, $AssertCreated )
    {
        if ( $AssertCreated )
        {
            $DirToCreate = $Path;
            if ( $IsFile )
                $DirToCreate = dirname( $Path );

            if ( !is_dir( $DirToCreate ) )
                mkdir( $DirToCreate, $this->DirMask, true );


            if ( $IsFile && !is_file( $Path ) )
                touch( $Path );
        }
        return $Path;
    }

    /**
     *
     * @var string
     */
    protected $Value;

    /**
     *
     * @var int
     */
    protected $DirMask = 0755;

}
