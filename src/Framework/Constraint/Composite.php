<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PhpUnit\Framework\Constraint;
use PhpUnit\Framework\ExpectationFailedException;

/**
 * @since Class available since Release 3.1.0
 */
abstract class PHPUnit_Framework_Constraint_Composite extends Constraint
{
    /**
     * @var \PhpUnit\Framework\Constraint
     */
    protected $innerConstraint;

    /**
     * @param \PhpUnit\Framework\Constraint $innerConstraint
     */
    public function __construct(Constraint $innerConstraint)
    {
        parent::__construct();
        $this->innerConstraint = $innerConstraint;
    }

    /**
     * Evaluates the constraint for parameter $other
     *
     * If $returnResult is set to false (the default), an exception is thrown
     * in case of a failure. null is returned otherwise.
     *
     * If $returnResult is true, the result of the evaluation is returned as
     * a boolean value instead: true in case of success, false in case of a
     * failure.
     *
     * @param  mixed                                        $other        Value or object to evaluate.
     * @param  string                                       $description  Additional information about the test
     * @param  bool                                         $returnResult Whether to return a result or throw an exception
     * @return mixed
     * @throws \PhpUnit\Framework\ExpectationFailedException
     */
    public function evaluate($other, $description = '', $returnResult = false)
    {
        try {
            return $this->innerConstraint->evaluate(
                $other,
                $description,
                $returnResult
            );
        } catch (ExpectationFailedException $e) {
            $this->fail($other, $description);
        }
    }

    /**
     * Counts the number of constraint elements.
     *
     * @return int
     */
    public function count()
    {
        return count($this->innerConstraint);
    }
}
