<?php

use Exception as PhpException;
use PhpUnit\Framework\TestCase;

class ExceptionInAssertPostConditionsTest extends TestCase
{
    public $setUp                = false;
    public $assertPreConditions  = false;
    public $assertPostConditions = false;
    public $tearDown             = false;
    public $testSomething        = false;

    protected function setUp()
    {
        $this->setUp = true;
    }

    protected function assertPreConditions()
    {
        $this->assertPreConditions = true;
    }

    public function testSomething()
    {
        $this->testSomething = true;
    }

    protected function assertPostConditions()
    {
        $this->assertPostConditions = true;
        throw new PhpException;
    }

    protected function tearDown()
    {
        $this->tearDown = true;
    }
}
