<?php

use PhpUnit\Framework\TestCase;

class CoverageNotProtectedTest extends TestCase
{
    /**
     * @covers CoveredClass::<!protected>
     */
    public function testSomething()
    {
        $o = new CoveredClass;
        $o->publicMethod();
    }
}
