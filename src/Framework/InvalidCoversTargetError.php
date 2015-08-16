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
 * case of a test that is skipped because of an invalid @covers annotation.
 *
 * @since Class available since Release 4.0.0
 */
class InvalidCoversTargetError extends AssertionFailedError implements SkippedTest
{
}
