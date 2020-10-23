<?php

namespace Qck;

/**
 * 
 * @author muellerm
 */
class CsvFileStorage implements Interfaces\Storage
{

    function __construct( string $path, string $delimiter = ",", string $enclosure = '"', string $escape = "\\" )
    {
        $this->path      = $path;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape    = $escape;
    }

    function record( $idx )
    {
        if ( !isset( $this->records[ $idx ] ) && $this->file )
        {
            $this->assertFile();
            $this->file->flock( LOCK_SH );
            $this->file->seek( $idx );
            if ( $this->file->valid() )
                $this->records[ $idx ] = $this->file->fgetcsv( $this->delimiter, $this->enclosure, $this->escape );
            $this->file->flock( LOCK_UN );
        }
        return isset( $this->records[ $idx ] ) ? $this->records[ $idx ] : null;
    }

    function records( callable $filter = null, $findFirst = false )
    {
        $records = [];
        $idx     = 0;
        while ( ($record  = $this->record( $idx ) ) )
        {
            ++$idx;

            if ( $filter && $filter( $record ) )
            {
                if ( $findFirst )
                    return $record;
                else
                    $record[] = $record;
            }
            else
                $record[] = $record;
        }
        return $records;
    }

    /**
     * 
     * @param mixed $id
     * @return Storage
     */
    function write( array $data, $idx = null )
    {
        $this->assertFile( true );
        $this->file->flock( LOCK_EX );
        $this->file->seek( $this->file->getSize() );
        $lineCount = $this->file->key();
        if ( $lineCount == 0 )
        {
            $this->file->fputcsv( array_keys( $data ), $this->delimiter, $this->enclosure, $this->escape );
            ++$lineCount;
        }
        if ( !$idx )
            $idx = $lineCount + 1;
        $this->file->seek( $idx );
        $this->file->fputcsv( array_values( $data ), $this->delimiter, $this->enclosure, $this->escape );
        $this->file->flock( LOCK_UN );
    }

    protected function assertFile( $createFileIfNecessary = false )
    {
        if ( !$this->file )
        {
            $parentDir  = dirname( $this->path );
            if ( !is_dir( $parentDir ) )
                mkdir( $parentDir, 0777, true );
            $mode       = "r+";
            if ( !file_exists( $this->path ) && $createFileIfNecessary )
                $mode       = "w+";
            $this->file = new \SplFileObject( $this->path, $mode );
        }
    }

    /**
     *
     * @var string
     */
    protected $path;

    /**
     *
     * @var string
     */
    protected $delimiter;

    /**
     *
     * @var string
     */
    protected $enclosure;

    /**
     *
     * @var string
     */
    protected $escape;

    // STATE

    /**
     *
     * @var \SplFileObject
     */
    private $file;

    /**
     *
     * @var array[]
     */
    private $records = [];

}
