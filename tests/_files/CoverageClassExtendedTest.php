<?php

use PhpUnit\Framework\TestCase;

class CoverageClassExtendedTest extends TestCase
{
    /**
     * @covers CoveredClass<extended>
     */
    public function testSomething()
    {
        $o = new CoveredClass;
        $o->publicMethod();
    }
}
