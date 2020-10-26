<?php

declare(strict_types=1);

namespace Qck\Tests;

use PHPUnit\Framework\TestCase;

final class CsvFileTest extends TestCase
{

    function setUp(): void
    {
        $this->tmpFile = tempnam( sys_get_temp_dir(), "CsvFileTest" );
    }

    function tearDown(): void
    {
        @unlink( $this->tmpFile );
    }

    public function test(): void
    {
        $csvFile = new \Qck\CsvFile( $this->tmpFile, ",", '"', "\\" );
        $biden   = [ "name" => "biden", "pw" => "test", "sex" => "m", "age" => "82" ];
        $trump   = [ "name" => "trump", "pw" => "test", "sex" => "m" ];
        $clinton = [ "name" => "clinton", "pw" => "test", "sex" => "f", "age" => "72" ];

        $csvFile->create( $biden );
        $csvFile->create( $trump );
        $csvFile->create( $clinton );
        $maleSelector = \Qck\ClosureConditions::create( function ( $record )
                {
                    return $record[ "sex" ] == "m";
                } );

        $allMales = $csvFile->select()->where( $maleSelector )->exec();
        $this->assertEquals( 2, count( $allMales ), "check if closure conditions work" );

        $allSortedByAge = $csvFile->select()->orderBy( "age" )->exec();
        $this->assertTrue( $allSortedByAge[ 0 ] == $clinton && $allSortedByAge[ 1 ] == $biden && $allSortedByAge[ 2 ] == $trump, "check if sorting works" );

        $allSortedByAgeDesc = $csvFile->select()->orderBy( "age", true )->exec();
        $this->assertTrue( $allSortedByAgeDesc[ 0 ] == $biden && $allSortedByAgeDesc[ 1 ] == $clinton && $allSortedByAgeDesc[ 2 ] == $trump, "check if descending sorting works" );

        $trumpSelector = \Qck\ClosureConditions::create( function ( $record )
                {
                    return $record[ "name" ] == "trump";
                } );
        $affectedRows = $csvFile->update( $trumpSelector, [ "age" => 76, "pw" => "nsa" ] );
        $this->assertEquals( 1, $affectedRows, "check if update works" );

        $allSortedByAge = $csvFile->select()->orderBy( "age" )->exec();
        $this->assertTrue( $allSortedByAge[ 2 ] == $biden && $allSortedByAge[ 0 ] == $clinton && $allSortedByAge[ 1 ][ "age" ] == 76, "check if descending sorting works after setting age on trump" );
                
        $trumpsPw = $csvFile->select()->columns("pw")->where($trumpSelector)->fetchColumn()->exec();
        $this->assertEquals( "nsa", $trumpsPw, "check if fetching columns work" );
        
    }

    protected $tmpFile;

}
