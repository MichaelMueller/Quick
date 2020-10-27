<?php

declare(strict_types=1);

namespace Qck\Tests;

use PHPUnit\Framework\TestCase;

final class ComparisonTest extends TestCase
{

    public function test(): void
    {
        /* @var $comparison \Qck\Interfaces\Comparison\Start */
        $comparison = new \Qck\Comparison();
        $this->assertNotNull( $comparison );

        $users = [
            [ "name" => "biden", "pw" => "test", "sex" => "m", "age" => "82" ],
            [ "name" => "trump", "pw" => "test", "sex" => "m", "age" => "76" ],
            [ "name" => "clinton", "pw" => "test", "sex" => "f", "age" => "72" ]
        ];

        $result                 = $comparison->variable( 0, "name" )->equals()->val( "biden" )->eval( $users );
        $this->assertTrue( $result );
        $result                 = $comparison->variable( 0, "name" )->equals()->val( "biden" )->and()->variable( 0, "pw" )->notEquals()->val( null )->eval( $users );
        $this->assertTrue( $result );
        $clintonIsMaleOrFemaleAndUnder75 = $comparison->variable( 2, "sex" )->equals()->val( "m" )->or()->parantheses()->variable( 2, "sex" )->equals()->val( "f" )->and()->variable( 2, "age" )->less()->val( 75 )->closeParantheses();
        $result                 = $clintonIsMaleOrFemaleAndUnder75->eval( $users );
        $this->assertTrue( $result );
    }

}
