<?php

namespace Qck\Interfaces;

/**
 *
 * @author muellerm
 */
interface Table
{

    /**
     * 
     * @return array
     */
    function records( callable $filter = null, $findFirst = false );

    /**
     * 
     * @param int $idx (zero based indexes)
     * @return array
     */
    function record( $idx );
    
    /**
     * 
     * @param int $idx (zero based indexes)
     * @return array
     */
    function delete( $idx );

    /**
     * 
     * @return int the new
     */
    function write( array $record, $idx = null );
}
