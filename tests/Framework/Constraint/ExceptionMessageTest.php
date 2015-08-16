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
use PhpUnit\Framework\TestCase;

/**
 * @since      Class available since Release 4.0.20
 * @covers     \PhpUnit\Framework\Constraint\ExceptionMessage
 */
class ExceptionMessageTest extends TestCase
{
    /**
     * @expectedException \Exception
     * @expectedExceptionMessage A literal exception message
     */
    public function testLiteralMessage()
    {
        throw new PhpException('A literal exception message');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage A partial
     */
    public function testPatialMessageBegin()
    {
        throw new PhpException('A partial exception message');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage partial exception
     */
    public function testPatialMessageMiddle()
    {
        throw new PhpException('A partial exception message');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage exception message
     */
    public function testPatialMessageEnd()
    {
        throw new PhpException('A partial exception message');
    }
}
