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

/**
 * Extension to PhpUnit\Framework\AssertionFailedError to mark the special
 * case of a skipped test.
 *
 * @since Class available since Release 3.0.0
 */
class SkippedTestError extends AssertionFailedError implements SkippedTest
{
}
