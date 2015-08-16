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
 * A Listener for test progress.
 *
 * @since      Interface available since Release 2.0.0
 */
interface TestListener
{
    /**
     * An error occurred.
     *
     * @param \PhpUnit\Framework\Test $test
     * @param \Exception              $e
     * @param float                   $time
     */
    public function addError(Test $test, PhpException $e, $time);

    /**
     * A failure occurred.
     *
     * @param \PhpUnit\Framework\Test                 $test
     * @param \PhpUnit\Framework\AssertionFailedError $e
     * @param float                                   $time
     */
    public function addFailure(Test $test, AssertionFailedError $e, $time);

    /**
     * Incomplete test.
     *
     * @param \PhpUnit\Framework\Test $test
     * @param \Exception              $e
     * @param float                   $time
     */
    public function addIncompleteTest(Test $test, PhpException $e, $time);

    /**
     * Risky test.
     *
     * @param \PhpUnit\Framework\Test $test
     * @param \Exception              $e
     * @param float                   $time
     * @since  Method available since Release 4.0.0
     */
    public function addRiskyTest(Test $test, PhpException $e, $time);

    /**
     * Skipped test.
     *
     * @param \PhpUnit\Framework\Test $test
     * @param \Exception              $e
     * @param float                   $time
     * @since  Method available since Release 3.0.0
     */
    public function addSkippedTest(Test $test, PhpException $e, $time);

    /**
     * A test suite started.
     *
     * @param \PhpUnit\Framework\TestSuite $suite
     * @since  Method available since Release 2.2.0
     */
    public function startTestSuite(TestSuite $suite);

    /**
     * A test suite ended.
     *
     * @param \PhpUnit\Framework\TestSuite $suite
     * @since  Method available since Release 2.2.0
     */
    public function endTestSuite(TestSuite $suite);

    /**
     * A test started.
     *
     * @param \PhpUnit\Framework\Test $test
     */
    public function startTest(Test $test);

    /**
     * A test ended.
     *
     * @param \PhpUnit\Framework\Test $test
     * @param float                  $time
     */
    public function endTest(Test $test, $time);
}
