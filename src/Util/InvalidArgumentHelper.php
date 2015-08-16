<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PhpUnit\Framework\Exception;

/**
 * Factory for PhpUnit\Framework\Exception objects that are used to describe
 * invalid arguments passed to a function or method.
 *
 * @since Class available since Release 3.4.0
 */
class PHPUnit_Util_InvalidArgumentHelper
{
    /**
     * @param  int                         $argument
     * @param  string                      $type
     * @param  mixed                       $value
     * @return \PhpUnit\Framework\Exception
     */
    public static function factory($argument, $type, $value = null)
    {
        $stack = debug_backtrace(false);

        return new Exception(
            sprintf(
                'Argument #%d%sof %s::%s() must be a %s',
                $argument,
                $value !== null ? ' (' . gettype($value) . '#' . $value . ')' : ' (No Value) ',
                $stack[1]['class'],
                $stack[1]['function'],
                $type
            )
        );
    }
}
