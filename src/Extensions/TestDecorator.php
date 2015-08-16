<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PhpUnit\Framework\Test;
use PhpUnit\Framework\TestResult;

/**
 * A Decorator for Tests.
 *
 * Use TestDecorator as the base class for defining new
 * test decorators. Test decorator subclasses can be introduced
 * to add behaviour before or after a test is run.
 *
 * @since Class available since Release 2.0.0
 */
class PHPUnit_Extensions_TestDecorator extends PHPUnit_Framework_Assert implements Test, PHPUnit_Framework_SelfDescribing
{
    /**
     * The Test to be decorated.
     *
     * @var object
     */
    protected $test = null;

    /**
     * Constructor.
     *
     * @param \PhpUnit\Framework\Test $test
     */
    public function __construct(Test $test)
    {
        $this->test = $test;
    }

    /**
     * Returns a string representation of the test.
     *
     * @return string
     */
    public function toString()
    {
        return $this->test->toString();
    }

    /**
     * Runs the test and collects the
     * result in a TestResult.
     *
     * @param \PhpUnit\Framework\TestResult $result
     */
    public function basicRun(TestResult $result)
    {
        $this->test->run($result);
    }

    /**
     * Counts the number of test cases that
     * will be run by this test.
     *
     * @return int
     */
    public function count()
    {
        return count($this->test);
    }

    /**
     * Creates a default TestResult object.
     *
     * @return \PhpUnit\Framework\TestResult
     */
    protected function createResult()
    {
        return new TestResult;
    }

    /**
     * Returns the test to be run.
     *
     * @return \PhpUnit\Framework\Test
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * Runs the decorated test and collects the
     * result in a TestResult.
     *
     * @param  \PhpUnit\Framework\TestResult $result
     * @return \PhpUnit\Framework\TestResult
     */
    public function run(TestResult $result = null)
    {
        if ($result === null) {
            $result = $this->createResult();
        }

        $this->basicRun($result);

        return $result;
    }
}
