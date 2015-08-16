<?php

use PhpUnit\Framework\BaseTestListener;
use PhpUnit\Framework\Test;

class BaseTestListenerSample extends BaseTestListener
{
    public $endCount = 0;

    public function endTest(Test $test, $time)
    {
        $this->endCount++;
    }
}
