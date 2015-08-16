<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PhpUnit\Framework\ExpectationFailedException;
use PhpUnit\Framework\Test;
use PhpUnit\Framework\TestCase;
use PhpUnit\Framework\TestFailure;
use PhpUnit\Framework\TestListener;
use PhpUnit\Framework\TestSuite;

/**
 * A TestListener that generates a logfile of the
 * test execution using the Test Anything Protocol (TAP).
 *
 * @since Class available since Release 3.0.0
 */
class PHPUnit_Util_Log_TAP extends PHPUnit_Util_Printer implements TestListener
{
    /**
     * @var int
     */
    protected $testNumber = 0;

    /**
     * @var int
     */
    protected $testSuiteLevel = 0;

    /**
     * @var bool
     */
    protected $testSuccessful = true;

    /**
     * Constructor.
     *
     * @param  mixed                       $out
     * @throws PHPUnit_Framework_Exception
     * @since  Method available since Release 3.3.4
     */
    public function __construct($out = null)
    {
        parent::__construct($out);
        $this->write("TAP version 13\n");
    }

    /**
     * An error occurred.
     *
     * @param \PhpUnit\Framework\Test $test
     * @param Exception               $e
     * @param float                   $time
     */
    public function addError(Test $test, Exception $e, $time)
    {
        $this->writeNotOk($test, 'Error');
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
        $this->writeNotOk($test, 'Failure');

        $message = explode(
            "\n",
            TestFailure::exceptionToString($e)
        );

        $diagnostic = [
          'message'  => $message[0],
          'severity' => 'fail'
        ];

        if ($e instanceof ExpectationFailedException) {
            $cf = $e->getComparisonFailure();

            if ($cf !== null) {
                $diagnostic['data'] = [
                  'got'      => $cf->getActual(),
                  'expected' => $cf->getExpected()
                ];
            }
        }

        $yaml = new Symfony\Component\Yaml\Dumper;

        $this->write(
            sprintf(
                "  ---\n%s  ...\n",
                $yaml->dump($diagnostic, 2, 2)
            )
        );
    }

    /**
     * Incomplete test.
     *
     * @param \PhpUnit\Framework\Test $test
     * @param Exception               $e
     * @param float                   $time
     */
    public function addIncompleteTest(Test $test, Exception $e, $time)
    {
        $this->writeNotOk($test, '', 'TODO Incomplete Test');
    }

    /**
     * Risky test.
     *
     * @param \PhpUnit\Framework\Test $test
     * @param Exception               $e
     * @param float                   $time
     * @since  Method available since Release 4.0.0
     */
    public function addRiskyTest(Test $test, Exception $e, $time)
    {
        $this->write(
            sprintf(
                "ok %d - # RISKY%s\n",
                $this->testNumber,
                $e->getMessage() != '' ? ' ' . $e->getMessage() : ''
            )
        );

        $this->testSuccessful = false;
    }

    /**
     * Skipped test.
     *
     * @param \PhpUnit\Framework\Test $test
     * @param Exception               $e
     * @param float                   $time
     * @since  Method available since Release 3.0.0
     */
    public function addSkippedTest(Test $test, Exception $e, $time)
    {
        $this->write(
            sprintf(
                "ok %d - # SKIP%s\n",
                $this->testNumber,
                $e->getMessage() != '' ? ' ' . $e->getMessage() : ''
            )
        );

        $this->testSuccessful = false;
    }

    /**
     * A testsuite started.
     *
     * @param \PhpUnit\Framework\TestSuite $suite
     */
    public function startTestSuite(TestSuite $suite)
    {
        $this->testSuiteLevel++;
    }

    /**
     * A testsuite ended.
     *
     * @param \PhpUnit\Framework\TestSuite $suite
     */
    public function endTestSuite(TestSuite $suite)
    {
        $this->testSuiteLevel--;

        if ($this->testSuiteLevel == 0) {
            $this->write(sprintf("1..%d\n", $this->testNumber));
        }
    }

    /**
     * A test started.
     *
     * @param \PhpUnit\Framework\Test $test
     */
    public function startTest(Test $test)
    {
        $this->testNumber++;
        $this->testSuccessful = true;
    }

    /**
     * A test ended.
     *
     * @param \PhpUnit\Framework\Test $test
     * @param float                   $time
     */
    public function endTest(Test $test, $time)
    {
        if ($this->testSuccessful === true) {
            $this->write(
                sprintf(
                    "ok %d - %s\n",
                    $this->testNumber,
                    PHPUnit_Util_Test::describe($test)
                )
            );
        }

        $this->writeDiagnostics($test);
    }

    /**
     * @param \PhpUnit\Framework\Test $test
     * @param string                  $prefix
     * @param string                  $directive
     */
    protected function writeNotOk(Test $test, $prefix = '', $directive = '')
    {
        $this->write(
            sprintf(
                "not ok %d - %s%s%s\n",
                $this->testNumber,
                $prefix != '' ? $prefix . ': ' : '',
                PHPUnit_Util_Test::describe($test),
                $directive != '' ? ' # ' . $directive : ''
            )
        );

        $this->testSuccessful = false;
    }

    /**
     * @param \PhpUnit\Framework\Test $test
     */
    private function writeDiagnostics(Test $test)
    {
        if (!$test instanceof TestCase) {
            return;
        }

        if (!$test->hasOutput()) {
            return;
        }

        foreach (explode("\n", trim($test->getActualOutput())) as $line) {
            $this->write(
                sprintf(
                    "# %s\n",
                    $line
                )
            );
        }
    }
}
