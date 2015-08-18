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

/**
 * @since      Class available since Release 2.0.0
 * @covers     \PhpUnit\Runner\BaseTestRunner
 */
class Runner_BaseTestRunnerTest extends TestCase
{
    public function testInvokeNonStaticSuite()
    {
        $runner = new MockRunner;
        $runner->getTest('NonStatic');
    }
}
