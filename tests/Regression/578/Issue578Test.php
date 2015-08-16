<?php

use Exception as PhpException;
use PhpUnit\Framework\TestCase;

class Issue578Test extends TestCase
{
    public function testNoticesDoublePrintStackTrace()
    {
        $this->iniSet('error_reporting', E_ALL | E_NOTICE);
        trigger_error('Stack Trace Test Notice', E_NOTICE);
    }

    public function testWarningsDoublePrintStackTrace()
    {
        $this->iniSet('error_reporting', E_ALL | E_NOTICE);
        trigger_error('Stack Trace Test Notice', E_WARNING);
    }

    public function testUnexpectedExceptionsPrintsCorrectly()
    {
        throw new PhpException('Double printed exception');
    }
}
