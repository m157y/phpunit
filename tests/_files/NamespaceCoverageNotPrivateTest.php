<?php

use PhpUnit\Framework\TestCase;

class NamespaceCoverageNotPrivateTest extends TestCase
{
    /**
     * @covers Foo\CoveredClass::<!private>
     */
    public function testSomething()
    {
        $o = new Foo\CoveredClass;
        $o->publicMethod();
    }
}
