<?php

use PhpUnit\Framework\TestCase;

class DependencyFailureTest extends TestCase
{
    public function testOne()
    {
        $this->fail();
    }

    /**
     * @depends testOne
     */
    public function testTwo()
    {
    }

    /**
     * @depends !clone testTwo
     */
    public function testThree()
    {
    }

    /**
     * @depends clone testOne
     */
    public function testFour()
    {
    }
}
