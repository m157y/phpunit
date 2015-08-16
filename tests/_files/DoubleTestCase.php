<?php

use PhpUnit\Framework\TestCase;
use PhpUnit\Framework\TestResult;

class DoubleTestCase implements PHPUnit_Framework_Test
{
    protected $testCase;

    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    public function count()
    {
        return 2;
    }

    public function run(TestResult $result = null)
    {
        $result->startTest($this);

        $this->testCase->runBare();
        $this->testCase->runBare();

        $result->endTest($this, 0);
    }
}
