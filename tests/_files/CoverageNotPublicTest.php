<?php

use PhpUnit\Framework\TestCase;

class CoverageNotPublicTest extends TestCase
{
    /**
     * @covers CoveredClass::<!public>
     */
    public function testSomething()
    {
        $o = new CoveredClass;
        $o->publicMethod();
    }
}
