<?php

use Exception as PhpException;
use PhpUnit\Framework\Exception;
use PhpUnit\Framework\ExpectationFailedException;
use PhpUnit\Framework\TestCase;

class ExceptionStackTest extends TestCase
{
    public function testPrintingChildException()
    {
        try {
            $this->assertEquals([1], [2], 'message');
        } catch (ExpectationFailedException $e) {
            $message = $e->getMessage() . $e->getComparisonFailure()->getDiff();
            throw new Exception("Child exception\n$message", 101, $e);
        }
    }

    public function testNestedExceptions()
    {
        $exceptionThree = new PhpException('Three');
        $exceptionTwo   = new InvalidArgumentException('Two', 0, $exceptionThree);
        $exceptionOne   = new PhpException('One', 0, $exceptionTwo);
        throw $exceptionOne;
    }
}
