<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpUnit\Extensions;

use PhpUnit\Framework\TestCase;
use PhpUnit\Framework\TestSuite;
use PhpUnit\Util\Test as TestUtil;
use PhpUnit\Util\TestSuiteIterator;
use RecursiveIteratorIterator;

/**
 * We have a TestSuite object A.
 * In TestSuite object A we have Tests tagged with @group.
 * We want a TestSuite object B that contains TestSuite objects C, D, ...
 * for the Tests tagged with @group C, @group D, ...
 * Running the Tests from TestSuite object B results in Tests tagged with both
 * @group C and @group D in TestSuite object A to be run twice .
 *
 * <code>
 * $suite = new PhpUnit\Extensions\GroupTestSuite($A, array('C', 'D'));
 * </code>
 *
 * @since Class available since Release 3.3.0
 */
class GroupTestSuite extends TestSuite
{
    public function __construct(TestSuite $suite, array $groups)
    {
        $groupSuites = [];
        $name        = $suite->getName();

        foreach ($groups as $group) {
            $groupSuites[$group] = new TestSuite($name . ' - ' . $group);
            $this->addTest($groupSuites[$group]);
        }

        $tests = new RecursiveIteratorIterator(
            new TestSuiteIterator($suite),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($tests as $test) {
            if ($test instanceof TestCase) {
                $testGroups = TestUtil::getGroups(
                    get_class($test),
                    $test->getName(false)
                );

                foreach ($groups as $group) {
                    foreach ($testGroups as $testGroup) {
                        if ($group == $testGroup) {
                            $groupSuites[$group]->addTest($test);
                        }
                    }
                }
            }
        }
    }
}
