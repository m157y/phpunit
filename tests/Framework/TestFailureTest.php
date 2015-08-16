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
use PhpUnit\Framework\TestFailure;

/**
 * @since      File available since Release 3.7.20
 */
class Framework_TestFailureTest extends TestCase
{
    /**
     * @covers \PhpUnit\Framework\TestFailure::toString
     */
    public function testToString()
    {
        $test      = new self(__FUNCTION__);
        $exception = new PHPUnit_Framework_Exception('message');
        $failure   = new TestFailure($test, $exception);

        $this->assertEquals(__METHOD__ . ': message', $failure->toString());
    }
}
