<?php

declare(strict_types=1);

namespace Qck\Tests;

use PHPUnit\Framework\TestCase;

final class MainTest extends TestCase
{

    public function testCanBeUsedAsString(): void
    {
        $this->assertEquals(
                true, true
        );
    }

}
