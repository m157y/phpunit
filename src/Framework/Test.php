<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PhpUnit\Framework\TestResult;

/**
 * A Test can be run and collect its results.
 *
 * @since      Interface available since Release 2.0.0
 */
interface PHPUnit_Framework_Test extends Countable
{
    /**
     * Runs a test and collects its result in a TestResult instance.
     *
     * @param  \PhpUnit\Framework\TestResult $result
     * @return \PhpUnit\Framework\TestResult
     */
    public function run(TestResult $result = null);
}
