<?php

use PhpUnit\Framework\TestCase;

class TestError extends TestCase
{
    protected function runTest()
    {
        throw new Exception;
    }
}
