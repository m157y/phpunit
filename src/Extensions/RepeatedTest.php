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
use PhpUnit\Framework\TestSuite;

/**
 * A Decorator that runs a test repeatedly.
 *
 * @since Class available since Release 2.0.0
 */
class PHPUnit_Extensions_RepeatedTest extends PHPUnit_Extensions_TestDecorator
{
    /**
     * @var bool
     */
    protected $processIsolation = false;

    /**
     * @var int
     */
    protected $timesRepeat = 1;

    /**
     * @param  \PhpUnit\Framework\Test     $test
     * @param  int                         $timesRepeat
     * @param  bool                        $processIsolation
     * @throws \PhpUnit\Framework\Exception
     */
    public function __construct(Test $test, $timesRepeat = 1, $processIsolation = false)
    {
        parent::__construct($test);

        if (is_integer($timesRepeat) &&
            $timesRepeat >= 0) {
            $this->timesRepeat = $timesRepeat;
        } else {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
                2,
                'positive integer'
            );
        }

        $this->processIsolation = $processIsolation;
    }

    /**
     * Counts the number of test cases that
     * will be run by this test.
     *
     * @return int
     */
    public function count()
    {
        return $this->timesRepeat * count($this->test);
    }

    /**
     * Runs the decorated test and collects the
     * result in a TestResult.
     *
     * @param  \PhpUnit\Framework\TestResult $result
     * @return \PhpUnit\Framework\TestResult
     * @throws \PhpUnit\Framework\Exception
     */
    public function run(TestResult $result = null)
    {
        if ($result === null) {
            $result = $this->createResult();
        }

        //@codingStandardsIgnoreStart
        for ($i = 0; $i < $this->timesRepeat && !$result->shouldStop(); $i++) {
            //@codingStandardsIgnoreEnd
            if ($this->test instanceof TestSuite) {
                $this->test->setRunTestInSeparateProcess($this->processIsolation);
            }
            $this->test->run($result);
        }

        return $result;
    }
}
