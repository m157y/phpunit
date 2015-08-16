<?php

use PhpUnit\Framework\TestCase;

class Issue1021Test extends TestCase
{
    /**
     * @dataProvider provider
     */
    public function testSomething($data)
    {
        $this->assertTrue($data);
    }

    /**
     * @depends testSomething
     */
    public function testSomethingElse()
    {
    }

    public function provider()
    {
        return [[true]];
    }
}
