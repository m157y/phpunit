<?php

use PhpUnit\Framework\TestCase;

class CoveragePrivateTest extends TestCase
{
    /**
     * @covers CoveredClass::<private>
     */
    public function testSomething()
    {
        $o = new CoveredClass;
        $o->publicMethod();
    }
}
