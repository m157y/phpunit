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

/**
 * Constraint that asserts that the string it is evaluated for begins with a
 * given prefix.
 *
 * @since Class available since Release 3.4.0
 */
class PHPUnit_Framework_Constraint_StringStartsWith extends Constraint
{
    /**
     * @var string
     */
    protected $prefix;

    /**
     * @param string $prefix
     */
    public function __construct($prefix)
    {
        parent::__construct();
        $this->prefix = $prefix;
    }

    /**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param  mixed $other Value or object to evaluate.
     * @return bool
     */
    protected function matches($other)
    {
        return strpos($other, $this->prefix) === 0;
    }

    /**
     * Returns a string representation of the constraint.
     *
     * @return string
     */
    public function toString()
    {
        return 'starts with "' . $this->prefix . '"';
    }
}
