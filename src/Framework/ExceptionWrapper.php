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

use PhpUnit\Util\Filter;

/**
 * Wraps Exceptions thrown by code under test.
 *
 * Re-instantiates Exceptions thrown by user-space code to retain their original
 * class names, properties, and stack traces (but without arguments).
 *
 * Unlike PhpUnit\Framework\Exception, the complete stack of previous Exceptions
 * is processed.
 *
 * @since Class available since Release 4.3.0
 */
class ExceptionWrapper extends Exception
{
    /**
     * @var string
     */
    protected $classname;

    /**
     * @var \PhpUnit\Framework\ExceptionWrapper|null
     */
    protected $previous;

    /**
     * @param \Throwable|\Exception $e
     */
    public function __construct($e)
    {
        // PDOException::getCode() is a string.
        // @see http://php.net/manual/en/class.pdoexception.php#95812
        parent::__construct($e->getMessage(), (int) $e->getCode());

        $this->classname = get_class($e);
        $this->file      = $e->getFile();
        $this->line      = $e->getLine();

        $this->serializableTrace = $e->getTrace();

        foreach ($this->serializableTrace as $i => $call) {
            unset($this->serializableTrace[$i]['args']);
        }

        if ($e->getPrevious()) {
            $this->previous = new self($e->getPrevious());
        }
    }

    /**
     * @return string
     */
    public function getClassname()
    {
        return $this->classname;
    }

    /**
     * @return \PhpUnit\Framework\ExceptionWrapper
     */
    public function getPreviousWrapped()
    {
        return $this->previous;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $string = TestFailure::exceptionToString($this);

        if ($trace = Filter::getFilteredStacktrace($this)) {
            $string .= "\n" . $trace;
        }

        if ($this->previous) {
            $string .= "\nCaused by\n" . $this->previous;
        }

        return $string;
    }
}
