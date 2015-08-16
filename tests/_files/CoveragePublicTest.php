<?php

use PhpUnit\Framework\TestCase;

class CoveragePublicTest extends TestCase
{
    /**
     * @covers CoveredClass::<public>
     */
    public function testSomething()
    {
        $o = new CoveredClass;
        $o->publicMethod();
    }
}
