<?php

use PhpUnit\Framework\TestCase;

class CoverageFunctionParenthesesTest extends TestCase
{
    /**
     * @covers ::globalFunction()
     */
    public function testSomething()
    {
        globalFunction();
    }
}
