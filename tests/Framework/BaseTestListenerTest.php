<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PhpUnit\Framework\TestCase;
use PhpUnit\Framework\TestResult;

/**
 * @since      Class available since Release 4.0.0
 */
class Framework_BaseTestListenerTest extends TestCase
{
    /**
     * @var \PhpUnit\Framework\TestResult
     */
    private $result;

    /**
     * @covers \PhpUnit\Framework\TestResult
     */
    public function testEndEventsAreCounted()
    {
        $this->result = new TestResult;
        $listener     = new BaseTestListenerSample();
        $this->result->addListener($listener);
        $test = new Success;
        $test->run($this->result);

        $this->assertEquals(1, $listener->endCount);
    }
}
