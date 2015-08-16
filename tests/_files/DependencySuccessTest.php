<?php

use PhpUnit\Framework\TestCase;

class DependencySuccessTest extends TestCase
{
    public function testOne()
    {
    }

    /**
     * @depends testOne
     */
    public function testTwo()
    {
    }

    /**
     * @depends DependencySuccessTest::testTwo
     */
    public function testThree()
    {
    }
}
