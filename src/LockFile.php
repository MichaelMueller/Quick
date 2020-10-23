<?php

namespace Qck;

/**
 * Description of BasicGit
 *
 * @author muellerm
 */
class LockFile implements Interfaces\LockFile
{

    function __construct( $path )
    {
        $this->path = $path;
    }

    public function read()
    {
        $this->assertHandle( false );
        $contents = file_exists( $this->path ) ? fread( $this->handle, filesize( $this->path ) ) : null;
        if ( !$this->writeAccess )
            $this->close();
        return $contents;
    }

    public function write( $contents )
    {
        $handle = $this->writeHandle( false );
        ftruncate( $handle, 0 );
        fwrite( $handle, $contents );
    }

    public function writeHandle( $endOfFile = false )
    {
        $this->assertHandle( true );
        fseek( $this->handle, 0, $endOfFile ? SEEK_END : SEEK_SET  );
        return $this->handle;
    }

    public function append( $line )
    {
        $handle = $this->writeHandle( true );
        fwrite( $handle, $line );
    }

    function close()
    {
        if ( $this->handle )
        {
            flock( $this->handle, LOCK_UN );
            fclose( $this->handle );
            $this->handle      = null;
            $this->writeAccess = false;
        }
    }

    protected function assertHandle( $writeAccess = true )
    {
        if ( !$this->handle || ($this->writeAccess == false && $writeAccess == true) )
        {
            if ( $this->handle )
                $this->close();

            $this->writeAccess = $writeAccess;
            $this->handle      = fopen( $this->path, $this->writeAccess ? "a+" : "r" );
            $lockMode          = $this->writeAccess ? LOCK_EX : LOCK_SH;
            $locked            = flock( $this->handle, $lockMode );
            if ( !$locked )
                throw new \Exception( "cannot lock " . $this->path );
        }
    }

    public function __destruct()
    {
        @$this->close();
    }

    protected $path;
    // state
    protected $handle      = null;
    protected $writeAccess = false;

}
