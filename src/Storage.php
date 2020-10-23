<?php

namespace Qck;

/**
 * 
 * @author muellerm
 */
class CsvStorage implements Interfaces\Storage
{

    function __construct( $path )
    {
        $this->path             = $path;
    }

    /**
     * Persist changes
     */
    function commit()
    {
        foreach ($this->records as $id => $record)
        {
            $path = $this->path( $id );
            file_put_contents( $path, $this->arraySerializer->serialize( $record ) );
        }
    }

    /**
     * 
     * @param mixed $id
     * @return array
     */
    function get( $id, $default = null )
    {
        $path = $this->path( $id );
        return file_exists( $path ) ? $this->arraySerializer->unserialize( file_get_contents( $path ) ) : $default;
    }

    /**
     * 
     * @param mixed $id
     * @return Storage
     */
    function set( array $data, $id = null )
    {
        $id                 = $id ? $id : $this->nextId();
        $this->records[$id] = $data;
    }

    protected function nextId()
    {
        $id = 0;
        do
        {
            ++$id;
            $path = $this->path( $id );
        }
        while (file_exists( $path ));

        $parentDir = pathname( $path );
        if (!is_path( $parentDir ))
            mkpath( $parentDir, 0777, true );
        touch( $path );
        return $id;
    }

    protected function path( $id )
    {
        return $this->path . "/" . $id . $this->arraySerializer->fileExtension();
    }


    /**
     *
     * @var Interfaces\LockFile
     */
    protected $lockFile;

    // STATE 

    /**
     *
     * @var array[]
     */
    protected $records;

}
