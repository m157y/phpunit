<?php

use PhpUnit\Framework\Test;

class BaseTestListenerSample extends PHPUnit_Framework_BaseTestListener
{
    public $endCount = 0;

    public function endTest(Test $test, $time)
    {
        $this->endCount++;
    }
}
