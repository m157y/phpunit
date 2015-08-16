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

use Exception as PhpException;
use SebastianBergmann\Comparator\ComparisonFailure;

/**
 * Exception for expectations which failed their check.
 *
 * The exception contains the error message and optionally a
 * SebastianBergmann\Comparator\ComparisonFailure which is used to
 * generate diff output of the failed expectations.
 *
 * @since Class available since Release 3.0.0
 */
class ExpectationFailedException extends AssertionFailedError
{
    /**
     * @var \SebastianBergmann\Comparator\ComparisonFailure
     */
    protected $comparisonFailure;

    public function __construct($message, ComparisonFailure $comparisonFailure = null, PhpException $previous = null)
    {
        $this->comparisonFailure = $comparisonFailure;

        parent::__construct($message, 0, $previous);
    }

    /**
     * @return \SebastianBergmann\Comparator\ComparisonFailure
     */
    public function getComparisonFailure()
    {
        return $this->comparisonFailure;
    }
}
