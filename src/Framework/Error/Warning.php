<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpUnit\Framework\Error;

use PhpUnit\Framework\Error;

/**
 * Wrapper for PHP warnings.
 * You can disable notice-to-exception conversion by setting
 *
 * <code>
 * PhpUnit\Framework\Error\Warning::$enabled = false;
 * </code>
 *
 * @since Class available since Release 3.3.0
 */
class Warning extends Error
{
    public static $enabled = true;
}
