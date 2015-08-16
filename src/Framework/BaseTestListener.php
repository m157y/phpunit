<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpUnit\Framework;

use Exception as PhpException;

/**
 * An empty Listener that can be extended to implement TestListener
 * with just a few lines of code.
 * @see \PhpUnit\Framework\TestListener for documentation on the API methods.
 *
 * @since Class available since Release 4.0.0
 */
abstract class BaseTestListener implements TestListener
{
    public function addError(Test $test, PhpException $e, $time)
    {
    }

    public function addFailure(Test $test, AssertionFailedError $e, $time)
    {
    }

    public function addIncompleteTest(Test $test, PhpException $e, $time)
    {
    }

    public function addRiskyTest(Test $test, PhpException $e, $time)
    {
    }

    public function addSkippedTest(Test $test, PhpException $e, $time)
    {
    }

    public function startTestSuite(TestSuite $suite)
    {
    }

    public function endTestSuite(TestSuite $suite)
    {
    }

    public function startTest(Test $test)
    {
    }

    public function endTest(Test $test, $time)
    {
    }
}
