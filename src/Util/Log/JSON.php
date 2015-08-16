<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Exception as PhpException;
use PhpUnit\Framework\Test;
use PhpUnit\Framework\TestListener;
use PhpUnit\Framework\TestSuite;

/**
 * A TestListener that generates JSON messages.
 *
 * @since Class available since Release 3.0.0
 */
class PHPUnit_Util_Log_JSON extends PHPUnit_Util_Printer implements TestListener
{
    /**
     * @var string
     */
    protected $currentTestSuiteName = '';

    /**
     * @var string
     */
    protected $currentTestName = '';

    /**
     * @var bool
     */
    protected $currentTestPass = true;

    /**
     * An error occurred.
     *
     * @param \PhpUnit\Framework\Test $test
     * @param \Exception              $e
     * @param float                   $time
     */
    public function addError(Test $test, PhpException $e, $time)
    {
        $this->writeCase(
            'error',
            $time,
            PHPUnit_Util_Filter::getFilteredStacktrace($e, false),
            $e->getMessage(),
            $test
        );

        $this->currentTestPass = false;
    }

    /**
     * A failure occurred.
     *
     * @param \PhpUnit\Framework\Test                $test
     * @param PHPUnit_Framework_AssertionFailedError $e
     * @param float                                  $time
     */
    public function addFailure(Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
    {
        $this->writeCase(
            'fail',
            $time,
            PHPUnit_Util_Filter::getFilteredStacktrace($e, false),
            $e->getMessage(),
            $test
        );

        $this->currentTestPass = false;
    }

    /**
     * Incomplete test.
     *
     * @param \PhpUnit\Framework\Test $test
     * @param \Exception              $e
     * @param float                   $time
     */
    public function addIncompleteTest(Test $test, PhpException $e, $time)
    {
        $this->writeCase(
            'error',
            $time,
            PHPUnit_Util_Filter::getFilteredStacktrace($e, false),
            'Incomplete Test: ' . $e->getMessage(),
            $test
        );

        $this->currentTestPass = false;
    }

    /**
     * Risky test.
     *
     * @param \PhpUnit\Framework\Test $test
     * @param \Exception              $e
     * @param float                   $time
     * @since  Method available since Release 4.0.0
     */
    public function addRiskyTest(Test $test, PhpException $e, $time)
    {
        $this->writeCase(
            'error',
            $time,
            PHPUnit_Util_Filter::getFilteredStacktrace($e, false),
            'Risky Test: ' . $e->getMessage(),
            $test
        );

        $this->currentTestPass = false;
    }

    /**
     * Skipped test.
     *
     * @param \PhpUnit\Framework\Test $test
     * @param \Exception              $e
     * @param float                   $time
     */
    public function addSkippedTest(Test $test, PhpException $e, $time)
    {
        $this->writeCase(
            'error',
            $time,
            PHPUnit_Util_Filter::getFilteredStacktrace($e, false),
            'Skipped Test: ' . $e->getMessage(),
            $test
        );

        $this->currentTestPass = false;
    }

    /**
     * A testsuite started.
     *
     * @param \PhpUnit\Framework\TestSuite $suite
     */
    public function startTestSuite(TestSuite $suite)
    {
        $this->currentTestSuiteName = $suite->getName();
        $this->currentTestName      = '';

        $this->write(
            [
            'event' => 'suiteStart',
            'suite' => $this->currentTestSuiteName,
            'tests' => count($suite)
            ]
        );
    }

    /**
     * A testsuite ended.
     *
     * @param \PhpUnit\Framework\TestSuite $suite
     */
    public function endTestSuite(TestSuite $suite)
    {
        $this->currentTestSuiteName = '';
        $this->currentTestName      = '';
    }

    /**
     * A test started.
     *
     * @param \PhpUnit\Framework\Test $test
     */
    public function startTest(Test $test)
    {
        $this->currentTestName = PHPUnit_Util_Test::describe($test);
        $this->currentTestPass = true;

        $this->write(
            [
            'event' => 'testStart',
            'suite' => $this->currentTestSuiteName,
            'test'  => $this->currentTestName
            ]
        );
    }

    /**
     * A test ended.
     *
     * @param \PhpUnit\Framework\Test $test
     * @param float                   $time
     */
    public function endTest(Test $test, $time)
    {
        if ($this->currentTestPass) {
            $this->writeCase('pass', $time, [], '', $test);
        }
    }

    /**
     * @param string                           $status
     * @param float                            $time
     * @param array                            $trace
     * @param string                           $message
     * @param \PhpUnit\Framework\TestCase|null $test
     */
    protected function writeCase($status, $time, array $trace = [], $message = '', $test = null)
    {
        $output = '';
        // take care of TestSuite producing error (e.g. by running into exception) as TestSuite doesn't have hasOutput
        if ($test !== null && method_exists($test, 'hasOutput') && $test->hasOutput()) {
            $output = $test->getActualOutput();
        }
        $this->write(
            [
            'event'   => 'test',
            'suite'   => $this->currentTestSuiteName,
            'test'    => $this->currentTestName,
            'status'  => $status,
            'time'    => $time,
            'trace'   => $trace,
            'message' => PHPUnit_Util_String::convertToUtf8($message),
            'output'  => $output,
            ]
        );
    }

    /**
     * @param string $buffer
     */
    public function write($buffer)
    {
        array_walk_recursive($buffer, function (&$input) {
            if (is_string($input)) {
                $input = PHPUnit_Util_String::convertToUtf8($input);
            }
        });

        parent::write(json_encode($buffer, JSON_PRETTY_PRINT));
    }
}
