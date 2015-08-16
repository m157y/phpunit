<?php

use PhpUnit\Framework\TestCase;

class CoverageMethodParenthesesTest extends TestCase
{
    /**
     * @covers CoveredClass::publicMethod()
     */
    public function testSomething()
    {
        $o = new CoveredClass;
        $o->publicMethod();
    }
}
