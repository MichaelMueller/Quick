<?php

namespace Qck;

/**
 * A class for exclusive writing to a File (other writing processes will wait until
 * another writing operation has finished)
 * 
 * @author muellerm
 */
class ExclusiveDataFile
{

    function __construct( $path, $writeMode )
    {
        $this->load( $path, $writeMode );
    }

    function getData()
    {
        return $this->data;
    }

    function clear()
    {
        $this->data = [];
    }

    function setData( $data )
    {
        if ( !$this->fp )
            throw new \Exception( "File not opened for writing. Cannot write." );

        rewind( $this->fp );
        ftruncate( $this->fp, 0 );
        rewind( $this->fp );
        fwrite( $this->fp, $data ? serialize( $data ) : null  );
    }

    public function __destruct()
    {
        $this->close();
    }

    protected function load( $path, $writeMode )
    {
        if ( !file_exists( $path ) )
        {
            $dir = dirname( $path );
            if ( !file_exists( $dir ) )
                mkdir( $dir, 0777, true );
            touch( $path );
        }
        $this->fp   = fopen( $path, "r+" );
        if ( $writeMode )
            flock( $this->fp, LOCK_EX );
        else
            flock( $this->fp, LOCK_SH );
        $fsize      = filesize( $path );
        $rawData    = $fsize > 0 ? fread( $this->fp, $fsize ) : null;
        if ( $rawData )
            $this->data = unserialize( $rawData );
        if ( !$writeMode )
            $this->close();
    }

    protected function close()
    {

        if ( $this->fp )
        {
            fflush( $this->fp ); // leere Ausgabepuffer bevor die Sperre frei gegeben wird        
            flock( $this->fp, LOCK_UN );
            fclose( $this->fp );
            $this->fp = null;
        }
    }

    protected $fp;
    protected $data;

}
