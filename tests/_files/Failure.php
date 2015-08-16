<?php

use PhpUnit\Framework\TestCase;

class Failure extends TestCase
{
    protected function runTest()
    {
        $this->fail();
    }
}
