<?php

use Exception as PhpException;
use PhpUnit\Framework\TestCase;

/**
 * @requires extension nonExistingExtension
 */
class RequirementsClassBeforeClassHookTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        throw new PhpException(__METHOD__ . ' should not be called because of class requirements.');
    }
}
