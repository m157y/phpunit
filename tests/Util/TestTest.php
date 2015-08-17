<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PhpUnit\Framework\TestCase;
use PhpUnit\Util\Test as TestUtil;

if (!defined('TEST_FILES_PATH')) {
    define(
        'TEST_FILES_PATH',
        dirname(__DIR__) . DIRECTORY_SEPARATOR .
        '_files' . DIRECTORY_SEPARATOR
    );
}

/**
 * @since      Class available since Release 3.3.6
 */
class Util_TestTest extends TestCase
{
    /**
     * @covers \PhpUnit\Util\Test::getExpectedException
     * @todo   Split up in separate tests
     */
    public function testGetExpectedException()
    {
        $this->assertArraySubset(
            ['class' => 'FooBarBaz', 'code' => null, 'message' => ''],
            TestUtil::getExpectedException('ExceptionTest', 'testOne')
        );

        $this->assertArraySubset(
            ['class' => 'Foo_Bar_Baz', 'code' => null, 'message' => ''],
            TestUtil::getExpectedException('ExceptionTest', 'testTwo')
        );

        $this->assertArraySubset(
            ['class' => 'Foo\Bar\Baz', 'code' => null, 'message' => ''],
            TestUtil::getExpectedException('ExceptionTest', 'testThree')
        );

        $this->assertArraySubset(
            ['class' => 'ほげ', 'code' => null, 'message' => ''],
            TestUtil::getExpectedException('ExceptionTest', 'testFour')
        );

        $this->assertArraySubset(
            ['class' => 'Class', 'code' => 1234, 'message' => 'Message'],
            TestUtil::getExpectedException('ExceptionTest', 'testFive')
        );

        $this->assertArraySubset(
            ['class' => 'Class', 'code' => 1234, 'message' => 'Message'],
            TestUtil::getExpectedException('ExceptionTest', 'testSix')
        );

        $this->assertArraySubset(
            ['class' => 'Class', 'code' => 'ExceptionCode', 'message' => 'Message'],
            TestUtil::getExpectedException('ExceptionTest', 'testSeven')
        );

        $this->assertArraySubset(
            ['class' => 'Class', 'code' => 0, 'message' => 'Message'],
            TestUtil::getExpectedException('ExceptionTest', 'testEight')
        );

        $this->assertArraySubset(
            ['class' => 'Class', 'code' => ExceptionTest::ERROR_CODE, 'message' => ExceptionTest::ERROR_MESSAGE],
            TestUtil::getExpectedException('ExceptionTest', 'testNine')
        );

        $this->assertArraySubset(
            ['class' => 'Class', 'code' => null, 'message' => ''],
            TestUtil::getExpectedException('ExceptionTest', 'testSingleLine')
        );

        $this->assertArraySubset(
            ['class' => 'Class', 'code' => My\Space\ExceptionNamespaceTest::ERROR_CODE, 'message' => My\Space\ExceptionNamespaceTest::ERROR_MESSAGE],
            TestUtil::getExpectedException('My\Space\ExceptionNamespaceTest', 'testConstants')
        );

        // Ensure the Class::CONST expression is only evaluated when the constant really exists
        $this->assertArraySubset(
            ['class' => 'Class', 'code' => 'ExceptionTest::UNKNOWN_CODE_CONSTANT', 'message' => 'ExceptionTest::UNKNOWN_MESSAGE_CONSTANT'],
            TestUtil::getExpectedException('ExceptionTest', 'testUnknownConstants')
        );

        $this->assertArraySubset(
            ['class' => 'Class', 'code' => 'My\Space\ExceptionNamespaceTest::UNKNOWN_CODE_CONSTANT', 'message' => 'My\Space\ExceptionNamespaceTest::UNKNOWN_MESSAGE_CONSTANT'],
            TestUtil::getExpectedException('My\Space\ExceptionNamespaceTest', 'testUnknownConstants')
        );
    }

    /**
     * @covers \PhpUnit\Util\Test::getExpectedException
     */
    public function testGetExpectedRegExp()
    {
        $this->assertArraySubset(
            ['message_regex' => '#regex#'],
            TestUtil::getExpectedException('ExceptionTest', 'testWithRegexMessage')
        );

        $this->assertArraySubset(
            ['message_regex' => '#regex#'],
            TestUtil::getExpectedException('ExceptionTest', 'testWithRegexMessageFromClassConstant')
        );

        $this->assertArraySubset(
            ['message_regex' => 'ExceptionTest::UNKNOWN_MESSAGE_REGEX_CONSTANT'],
            TestUtil::getExpectedException('ExceptionTest', 'testWithUnknowRegexMessageFromClassConstant')
        );
    }

    /**
     * @covers       \PhpUnit\Util\Test::getRequirements
     * @dataProvider requirementsProvider
     */
    public function testGetRequirements($test, $result)
    {
        $this->assertEquals(
            $result,
            TestUtil::getRequirements('RequirementsTest', $test)
        );
    }

    public function requirementsProvider()
    {
        return [
            ['testOne',    []],
            ['testTwo',    ['PHPUnit' => '1.0']],
            ['testThree',  ['PHP' => '2.0']],
            ['testFour',   ['PHPUnit'=>'2.0', 'PHP' => '1.0']],
            ['testFive',   ['PHP' => '5.4.0RC6']],
            ['testSix',    ['PHP' => '5.4.0-alpha1']],
            ['testSeven',  ['PHP' => '5.4.0beta2']],
            ['testEight',  ['PHP' => '5.4-dev']],
            ['testNine',   ['functions' => ['testFunc']]],
            ['testTen',    ['extensions' => ['testExt']]],
            ['testEleven', ['OS' => '/Linux/i']],
            [
              'testSpace',
              [
                'extensions' => ['spl'],
                'OS'         => '/.*/i'
              ]
            ],
            [
              'testAllPossibleRequirements',
              [
                'PHP'       => '99-dev',
                'PHPUnit'   => '9-dev',
                'OS'        => '/DOESNOTEXIST/i',
                'functions' => [
                  'testFuncOne',
                  'testFuncTwo',
                ],
                'extensions' => [
                  'testExtOne',
                  'testExtTwo',
                ]
              ]
            ]
        ];
    }

    /**
     * @covers \PhpUnit\Util\Test::getRequirements
     */
    public function testGetRequirementsMergesClassAndMethodDocBlocks()
    {
        $expectedAnnotations = [
            'PHP'       => '5.4',
            'PHPUnit'   => '3.7',
            'OS'        => '/WINNT/i',
            'functions' => [
              'testFuncClass',
              'testFuncMethod',
            ],
            'extensions' => [
              'testExtClass',
              'testExtMethod',
            ]
        ];

        $this->assertEquals(
            $expectedAnnotations,
            TestUtil::getRequirements('RequirementsClassDocBlockTest', 'testMethod')
        );
    }

    /**
     * @covers       \PhpUnit\Util\Test::getMissingRequirements
     * @dataProvider missingRequirementsProvider
     */
    public function testGetMissingRequirements($test, $result)
    {
        $this->assertEquals(
            $result,
            TestUtil::getMissingRequirements('RequirementsTest', $test)
        );
    }

    public function missingRequirementsProvider()
    {
        return [
            ['testOne',            []],
            ['testNine',           ['Function testFunc is required.']],
            ['testTen',            ['Extension testExt is required.']],
            ['testAlwaysSkip',     ['PHPUnit 1111111 (or later) is required.']],
            ['testAlwaysSkip2',    ['PHP 9999999 (or later) is required.']],
            ['testAlwaysSkip3',    ['Operating system matching /DOESNOTEXIST/i is required.']],
            ['testAllPossibleRequirements', [
              'PHP 99-dev (or later) is required.',
              'PHPUnit 9-dev (or later) is required.',
              'Operating system matching /DOESNOTEXIST/i is required.',
              'Function testFuncOne is required.',
              'Function testFuncTwo is required.',
              'Extension testExtOne is required.',
              'Extension testExtTwo is required.',
            ]],
        ];
    }

    /**
     * @coversNothing
     * @todo   This test does not really test functionality of PhpUnit\Util\Test
     */
    public function testGetProvidedDataRegEx()
    {
        $result = preg_match(TestUtil::REGEX_DATA_PROVIDER, '@dataProvider method', $matches);
        $this->assertEquals(1, $result);
        $this->assertEquals('method', $matches[1]);

        $result = preg_match(TestUtil::REGEX_DATA_PROVIDER, '@dataProvider class::method', $matches);
        $this->assertEquals(1, $result);
        $this->assertEquals('class::method', $matches[1]);

        $result = preg_match(TestUtil::REGEX_DATA_PROVIDER, '@dataProvider namespace\class::method', $matches);
        $this->assertEquals(1, $result);
        $this->assertEquals('namespace\class::method', $matches[1]);

        $result = preg_match(TestUtil::REGEX_DATA_PROVIDER, '@dataProvider namespace\namespace\class::method', $matches);
        $this->assertEquals(1, $result);
        $this->assertEquals('namespace\namespace\class::method', $matches[1]);

        $result = preg_match(TestUtil::REGEX_DATA_PROVIDER, '@dataProvider メソッド', $matches);
        $this->assertEquals(1, $result);
        $this->assertEquals('メソッド', $matches[1]);
    }

    /**
     * @covers \PhpUnit\Util\Test::getDataFromTestWithAnnotation
     */
    public function testTestWithEmptyAnnotation()
    {
        $result = TestUtil::getDataFromTestWithAnnotation("/**\n * @anotherAnnotation\n */");
        $this->assertNull($result);
    }

    /**
     * @covers \PhpUnit\Util\Test::getDataFromTestWithAnnotation
     */
    public function testTestWithSimpleCase()
    {
        $result = TestUtil::getDataFromTestWithAnnotation('/**
                                                                     * @testWith [1]
                                                                     */');
        $this->assertEquals([[1]], $result);
    }

    /**
     * @covers \PhpUnit\Util\Test::getDataFromTestWithAnnotation
     */
    public function testTestWithMultiLineMultiParameterCase()
    {
        $result = TestUtil::getDataFromTestWithAnnotation('/**
                                                                     * @testWith [1, 2]
                                                                     * [3, 4]
                                                                     */');
        $this->assertEquals([[1, 2], [3, 4]], $result);
    }

    /**
     * @covers \PhpUnit\Util\Test::getDataFromTestWithAnnotation
     */
    public function testTestWithVariousTypes()
    {
        $result = TestUtil::getDataFromTestWithAnnotation('/**
            * @testWith ["ab"]
            *           [true]
            *           [null]
         */');
        $this->assertEquals([['ab'], [true], [null]], $result);
    }

    /**
     * @covers \PhpUnit\Util\Test::getDataFromTestWithAnnotation
     */
    public function testTestWithAnnotationAfter()
    {
        $result = TestUtil::getDataFromTestWithAnnotation('/**
                                                            * @testWith [1]
                                                            *           [2]
                                                            * @annotation
                                                            */');
        $this->assertEquals([[1], [2]], $result);
    }

    /**
     * @covers \PhpUnit\Util\Test::getDataFromTestWithAnnotation
     */
    public function testTestWithSimpleTextAfter()
    {
        $result = TestUtil::getDataFromTestWithAnnotation('/**
                                                            * @testWith [1]
                                                            *           [2]
                                                            * blah blah
                                                            */');
        $this->assertEquals([[1], [2]], $result);
    }

    /**
     * @covers \PhpUnit\Util\Test::getDataFromTestWithAnnotation
     */
    public function testTestWithCharacterEscape()
    {
        $result = TestUtil::getDataFromTestWithAnnotation('/**
                                                            * @testWith ["\"", "\""]
                                                            */');
        $this->assertEquals([['"', '"']], $result);
    }

    /**
     * @covers \PhpUnit\Util\Test::getDataFromTestWithAnnotation
     */
    public function testTestWithThrowsProperExceptionIfDatasetCannotBeParsed()
    {
        $this->setExpectedExceptionRegExp(
            'PhpUnit\\Framework\\Exception',
            '/^The dataset for the @testWith annotation cannot be parsed.$/'
        );
        TestUtil::getDataFromTestWithAnnotation('/**
                                                  * @testWith [s]
                                                  */');
    }

    /**
     * @covers \PhpUnit\Util\Test::getDependencies
     * @todo   Not sure what this test tests (name is misleading at least)
     */
    public function testParseAnnotation()
    {
        $this->assertEquals(
            ['Foo', 'ほげ'],
            TestUtil::getDependencies(get_class($this), 'methodForTestParseAnnotation')
        );
    }

    /**
     * @depends Foo
     * @depends ほげ
     * @todo    Remove fixture from test class
     */
    public function methodForTestParseAnnotation()
    {
    }

    /**
     * @covers \PhpUnit\Util\Test::getDependencies
     */
    public function testParseAnnotationThatIsOnlyOneLine()
    {
        $this->assertEquals(
            ['Bar'],
            TestUtil::getDependencies(get_class($this), 'methodForTestParseAnnotationThatIsOnlyOneLine')
        );
    }

    /** @depends Bar */
    public function methodForTestParseAnnotationThatIsOnlyOneLine()
    {
        // TODO Remove fixture from test class
    }

    /**
     * @covers       \PhpUnit\Util\Test::getLinesToBeCovered
     * @covers       \PhpUnit\Util\Test::getLinesToBeCoveredOrUsed
     * @covers       \PhpUnit\Util\Test::resolveElementToReflectionObjects
     * @dataProvider getLinesToBeCoveredProvider
     */
    public function testGetLinesToBeCovered($test, $lines)
    {
        if (strpos($test, 'Namespace') === 0) {
            $expected = [
              TEST_FILES_PATH . 'NamespaceCoveredClass.php' => $lines
            ];
        } elseif ($test === 'CoverageNoneTest') {
            $expected = [];
        } elseif ($test === 'CoverageNothingTest') {
            $expected = false;
        } elseif ($test === 'CoverageFunctionTest') {
            $expected = [
              TEST_FILES_PATH . 'CoveredFunction.php' => $lines
            ];
        } else {
            $expected = [TEST_FILES_PATH . 'CoveredClass.php' => $lines];
        }

        $this->assertEquals(
            $expected,
            TestUtil::getLinesToBeCovered(
                $test, 'testSomething'
            )
        );
    }

    /**
     * @covers            \PhpUnit\Util\Test::getLinesToBeCovered
     * @covers            \PhpUnit\Util\Test::getLinesToBeCoveredOrUsed
     * @covers            \PhpUnit\Util\Test::resolveElementToReflectionObjects
     * @expectedException \PhpUnit\Framework\CodeCoverageException
     */
    public function testGetLinesToBeCovered2()
    {
        TestUtil::getLinesToBeCovered(
            'NotExistingCoveredElementTest', 'testOne'
        );
    }

    /**
     * @covers            \PhpUnit\Util\Test::getLinesToBeCovered
     * @covers            \PhpUnit\Util\Test::getLinesToBeCoveredOrUsed
     * @covers            \PhpUnit\Util\Test::resolveElementToReflectionObjects
     * @expectedException \PhpUnit\Framework\CodeCoverageException
     */
    public function testGetLinesToBeCovered3()
    {
        TestUtil::getLinesToBeCovered(
            'NotExistingCoveredElementTest', 'testTwo'
        );
    }

    /**
     * @covers            \PhpUnit\Util\Test::getLinesToBeCovered
     * @covers            \PhpUnit\Util\Test::getLinesToBeCoveredOrUsed
     * @covers            \PhpUnit\Util\Test::resolveElementToReflectionObjects
     * @expectedException \PhpUnit\Framework\CodeCoverageException
     */
    public function testGetLinesToBeCovered4()
    {
        TestUtil::getLinesToBeCovered(
            'NotExistingCoveredElementTest', 'testThree'
        );
    }

    /**
     * @covers \PhpUnit\Util\Test::getLinesToBeCovered
     * @covers \PhpUnit\Util\Test::getLinesToBeCoveredOrUsed
     */
    public function testGetLinesToBeCoveredSkipsNonExistentMethods()
    {
        $this->assertSame(
            [],
            TestUtil::getLinesToBeCovered(
                'NotExistingCoveredElementTest',
                'methodDoesNotExist'
            )
        );
    }

    /**
     * @covers            \PhpUnit\Util\Test::getLinesToBeCovered
     * @covers            \PhpUnit\Util\Test::getLinesToBeCoveredOrUsed
     * @expectedException \PhpUnit\Framework\CodeCoverageException
     */
    public function testTwoCoversDefaultClassAnnoationsAreNotAllowed()
    {
        TestUtil::getLinesToBeCovered(
            'CoverageTwoDefaultClassAnnotations',
            'testSomething'
        );
    }

    /**
     * @covers \PhpUnit\Util\Test::getLinesToBeCovered
     * @covers \PhpUnit\Util\Test::getLinesToBeCoveredOrUsed
     */
    public function testFunctionParenthesesAreAllowed()
    {
        $this->assertSame(
            [TEST_FILES_PATH . 'CoveredFunction.php' => range(2, 4)],
            TestUtil::getLinesToBeCovered(
                'CoverageFunctionParenthesesTest',
                'testSomething'
            )
        );
    }

    /**
     * @covers \PhpUnit\Util\Test::getLinesToBeCovered
     * @covers \PhpUnit\Util\Test::getLinesToBeCoveredOrUsed
     */
    public function testFunctionParenthesesAreAllowedWithWhitespace()
    {
        $this->assertSame(
            [TEST_FILES_PATH . 'CoveredFunction.php' => range(2, 4)],
            TestUtil::getLinesToBeCovered(
                'CoverageFunctionParenthesesWhitespaceTest',
                'testSomething'
            )
        );
    }

    /**
     * @covers \PhpUnit\Util\Test::getLinesToBeCovered
     * @covers \PhpUnit\Util\Test::getLinesToBeCoveredOrUsed
     */
    public function testMethodParenthesesAreAllowed()
    {
        $this->assertSame(
            [TEST_FILES_PATH . 'CoveredClass.php' => range(31, 35)],
            TestUtil::getLinesToBeCovered(
                'CoverageMethodParenthesesTest',
                'testSomething'
            )
        );
    }

    /**
     * @covers \PhpUnit\Util\Test::getLinesToBeCovered
     * @covers \PhpUnit\Util\Test::getLinesToBeCoveredOrUsed
     */
    public function testMethodParenthesesAreAllowedWithWhitespace()
    {
        $this->assertSame(
            [TEST_FILES_PATH . 'CoveredClass.php' => range(31, 35)],
            TestUtil::getLinesToBeCovered(
                'CoverageMethodParenthesesWhitespaceTest',
                'testSomething'
            )
        );
    }

    public function getLinesToBeCoveredProvider()
    {
        return [
          [
            'CoverageNoneTest',
            []
          ],
          [
            'CoverageClassExtendedTest',
            array_merge(range(19, 36), range(2, 17))
          ],
          [
            'CoverageClassTest',
            range(19, 36)
          ],
          [
            'CoverageMethodTest',
            range(31, 35)
          ],
          [
            'CoverageMethodOneLineAnnotationTest',
            range(31, 35)
          ],
          [
            'CoverageNotPrivateTest',
            array_merge(range(25, 29), range(31, 35))
          ],
          [
            'CoverageNotProtectedTest',
            array_merge(range(21, 23), range(31, 35))
          ],
          [
            'CoverageNotPublicTest',
            array_merge(range(21, 23), range(25, 29))
          ],
          [
            'CoveragePrivateTest',
            range(21, 23)
          ],
          [
            'CoverageProtectedTest',
            range(25, 29)
          ],
          [
            'CoveragePublicTest',
            range(31, 35)
          ],
          [
            'CoverageFunctionTest',
            range(2, 4)
          ],
          [
            'NamespaceCoverageClassExtendedTest',
            array_merge(range(21, 38), range(4, 19))
          ],
          [
            'NamespaceCoverageClassTest',
            range(21, 38)
          ],
          [
            'NamespaceCoverageMethodTest',
            range(33, 37)
          ],
          [
            'NamespaceCoverageNotPrivateTest',
            array_merge(range(27, 31), range(33, 37))
          ],
          [
            'NamespaceCoverageNotProtectedTest',
            array_merge(range(23, 25), range(33, 37))
          ],
          [
            'NamespaceCoverageNotPublicTest',
            array_merge(range(23, 25), range(27, 31))
          ],
          [
            'NamespaceCoveragePrivateTest',
            range(23, 25)
          ],
          [
            'NamespaceCoverageProtectedTest',
            range(27, 31)
          ],
          [
            'NamespaceCoveragePublicTest',
            range(33, 37)
          ],
          [
            'NamespaceCoverageCoversClassTest',
            array_merge(range(23, 25), range(27, 31), range(33, 37), range(6, 8), range(10, 13), range(15, 18))
          ],
          [
            'NamespaceCoverageCoversClassPublicTest',
            range(33, 37)
          ],
          [
            'CoverageNothingTest',
            false
          ]
        ];
    }
}
