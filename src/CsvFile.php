<?php

namespace Qck;

/**
 * 
 * @author muellerm
 */
class CsvFile implements Interfaces\Table
{

    function __construct( string $path, string $delimiter = ",", string $enclosure = '"', string $escape = "\\" )
    {
        $this->path      = $path;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape    = $escape;
    }

    public function create( array $record )
    {
        $this->assertLoaded();
        $this->records[] = $record;
        $this->writeRecords();
        return null;
    }

    public function delete( Interfaces\BooleanExpression $booleanExpression )
    {
        $indexes = $this->indexes( $booleanExpression );
        if ( $indexes )
        {
            foreach ( $indexes as $idx )
                unset( $this->records[ $idx ] );
            $this->records = array_values( $this->records );
            $this->writeRecords();
        }
        return count( $indexes );
    }

    public function execSelect( CsvFile\Select $select )
    {
        $matchingRecords = [];
        $indexes         = $this->indexes( $select->booleanExpression );

        if ( $indexes )
        {
            for ( $i = intval( $select->offset ); $i < count( $indexes ); $i++ )
            {
                if ( $select->limit && count( $matchingRecords ) == $select->limit )
                    break;
                $idx               = $indexes[ $i ];
                $matchingRecords[] = is_null( $select->columns ) ? $this->records[ $idx ] : array_intersect_key( $this->records[ $idx ], array_flip($select->columns) );
            }

            if ( $select->orderColumn )
                usort( $matchingRecords, function ( $a, $b ) use( $select )
                {
                    if ( !isset( $a[ $select->orderColumn ] ) && !isset( $b[ $select->orderColumn ] ) )
                        return 0;
                    else if ( !isset( $a[ $select->orderColumn ] ) && isset( $b[ $select->orderColumn ] ) )
                        return 1;
                    else if ( isset( $a[ $select->orderColumn ] ) && !isset( $b[ $select->orderColumn ] ) )
                        return -1;
                    return $select->orderDescending ? $b[ $select->orderColumn ] <=> $a[ $select->orderColumn ] : $a[ $select->orderColumn ] <=> $b[ $select->orderColumn ];
                } );
        }
        if ( $select->fetchRow && count( $matchingRecords ) > 0 )
            return $matchingRecords[ 0 ];
        else if ( $select->fetchColumn )
            return count( $matchingRecords ) > 0 && count( $matchingRecords[ 0 ] ) > 0 ? array_shift( $matchingRecords[ 0 ] ) : null;
        else
            return $matchingRecords;
    }

    public function update( Interfaces\BooleanExpression $booleanExpression, array $record )
    {
        $indexes = $this->indexes( $booleanExpression );
        if ( $indexes )
        {
            foreach ( $indexes as $idx )
                foreach ( $record as $key => $value )
                    $this->records[ $idx ][ $key ] = $value;

            $this->writeRecords();
        }
        return count( $indexes );
    }

    protected function indexes( Interfaces\BooleanExpression $booleanExpression = null )
    {
        $indexes = [];
        $this->assertLoaded();

        foreach ( $this->records as $idx => $record )
            if ( is_null( $booleanExpression ) || $booleanExpression->eval( $record ) )
                $indexes[] = $idx;

        return $indexes;
    }

    protected function writeRecords()
    {
        $parentDir = dirname( $this->path );
        if ( !is_dir( $parentDir ) )
            mkdir( $parentDir, 0777, true );
        $fp        = fopen( $this->path, 'w' );

        flock( $fp, LOCK_EX );

        foreach ( $this->records as $record )
            fputcsv( $fp, $record, $this->delimiter, $this->enclosure, $this->escape );

        flock( $fp, LOCK_UN );

        fclose( $fp );
    }

    protected function assertLoaded()
    {
        if ( $this->fileModifiedTime && $this->fileModifiedTime < filemtime( $this->path ) )
            $this->records = null;

        if ( is_null( $this->records ) )
        {
            $this->records = [];
            if ( file_exists( $this->path ) && filesize( $this->path ) > 0 )
            {
                $lines                  = file( $this->path, FILE_IGNORE_NEW_LINES );
                foreach ( $lines as $line )
                    $this->records[]        = str_getcsv( $line, $this->delimiter, $this->enclosure, $this->escape );
                $this->fileModifiedTime = filemtime( $this->path );
            }
        }
    }

    public function select(): Interfaces\Select
    {
        return new CsvFile\Select( $this );
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
     * @var int
     */
    private $fileModifiedTime;

    /**
     *
     * @var array[]
     */
    private $records;

}

namespace Qck\CsvFile;

class Select implements \Qck\Interfaces\Select
{

    function __construct( \Qck\CsvFile $csvFile )
    {
        $this->csvFile = $csvFile;
    }

    public function columns( ...$columns ): \Qck\Interfaces\Select
    {
        $this->columns = $columns;
        return $this;
    }

    public function fetchColumn(): \Qck\Interfaces\Select
    {

        $this->fetchColumn = true;
        return $this;
    }

    public function fetchRow(): \Qck\Interfaces\Select
    {
        $this->fetchRow = true;
        return $this;
    }

    public function limit( $limit ): \Qck\Interfaces\Select
    {

        $this->limit = $limit;
        return $this;
    }

    public function offset( $offset ): \Qck\Interfaces\Select
    {
        $this->offset = $offset;
        return $this;
    }

    public function orderBy( $orderColumn, $orderDescending = false ): \Qck\Interfaces\Select
    {
        $this->orderColumn     = $orderColumn;
        $this->orderDescending = $orderDescending;
        return $this;
    }

    public function where( \Qck\Interfaces\BooleanExpression $booleanExpression ): \Qck\Interfaces\Select
    {
        $this->booleanExpression = $booleanExpression;
        return $this;
    }

    public function exec()
    {
        return $this->csvFile->execSelect( $this );
    }

    /**
     *
     * @var \Qck\CsvFile
     */
    protected $csvFile;

    /**
     *
     * @var array
     */
    public $columns;

    /**
     *
     * @var \Qck\Interfaces\BooleanExpression
     */
    public $booleanExpression;

    /**
     *
     * @var int|null
     */
    public $limit;

    /**
     *
     * @var int|null
     */
    public $offset;

    /**
     *
     * @var string
     */
    public $orderColumn;

    /**
     *
     * @var bool
     */
    public $orderDescending = false;

    /**
     *
     * @var bool
     */
    public $fetchRow = false;

    /**
     *
     * @var bool
     */
    public $fetchColumn = false;

}
