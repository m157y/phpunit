<?php

use PhpUnit\Framework\TestCase;

class CoverageFunctionParenthesesWhitespaceTest extends TestCase
{
    /**
     * @covers ::globalFunction ( )
     */
    public function testSomething()
    {
        globalFunction();
    }
}
