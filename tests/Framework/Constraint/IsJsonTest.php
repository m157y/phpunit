<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PhpUnit\Framework\Constraint\IsJson;
use PhpUnit\Framework\TestCase;

class Framework_Constraint_IsJsonTest extends TestCase
{
    /**
     * @dataProvider evaluateDataprovider
     * @covers \PhpUnit\Framework\Constraint\IsJson::__construct
     * @covers \PhpUnit\Framework\Constraint\IsJson::evaluate
     * @covers \PhpUnit\Framework\Constraint\IsJson::matches
     */
    public function testEvaluate($expected, $jsonOther)
    {
        $constraint = new IsJson();
        $this->assertEquals($expected, $constraint->evaluate($jsonOther, '', true));
    }

    public static function evaluateDataprovider()
    {
        return [
            'valid JSON'                                     => [true, '{}'],
            'empty string should be treated as invalid JSON' => [false, ''],
        ];
    }
}
