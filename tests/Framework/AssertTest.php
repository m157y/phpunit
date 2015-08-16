<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PhpUnit\Framework\AssertionFailedError;
use PhpUnit\Framework\Exception;
use PhpUnit\Framework\ExpectationFailedException;
use PhpUnit\Framework\IncompleteTestError;
use PhpUnit\Framework\SkippedTestError;
use PhpUnit\Framework\TestCase;

/**
 * @since      Class available since Release 2.0.0
 */
class Framework_AssertTest extends TestCase
{
    private $filesDirectory;

    protected function setUp()
    {
        $this->filesDirectory = dirname(__DIR__) . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR;
    }

    /**
     * @covers \PhpUnit\Framework\Assert::fail
     */
    public function testFail()
    {
        try {
            $this->fail();
        } catch (AssertionFailedError $e) {
            return;
        }

        throw new AssertionFailedError('Fail did not throw fail exception');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertContains
     */
    public function testAssertSplObjectStorageContainsObject()
    {
        $a = new stdClass;
        $b = new stdClass;
        $c = new SplObjectStorage;
        $c->attach($a);

        $this->assertContains($a, $c);

        try {
            $this->assertContains($b, $c);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertContains
     */
    public function testAssertArrayContainsObject()
    {
        $a = new stdClass;
        $b = new stdClass;

        $this->assertContains($a, [$a]);

        try {
            $this->assertContains($a, [$b]);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertContains
     */
    public function testAssertArrayContainsString()
    {
        $this->assertContains('foo', ['foo']);

        try {
            $this->assertContains('foo', ['bar']);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertContains
     */
    public function testAssertArrayContainsNonObject()
    {
        $this->assertContains('foo', [true]);

        try {
            $this->assertContains('foo', [true], '', false, true, true);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertContainsOnlyInstancesOf
     */
    public function testAssertContainsOnlyInstancesOf()
    {
        $test = [
            new Book(),
            new Book
        ];
        $this->assertContainsOnlyInstancesOf('Book', $test);
        $this->assertContainsOnlyInstancesOf('stdClass', [new stdClass()]);

        $test2 = [
            new Author('Test')
        ];
        try {
            $this->assertContainsOnlyInstancesOf('Book', $test2);
        } catch (AssertionFailedError $e) {
            return;
        }
        $this->fail();
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertArrayHasKey
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertArrayHasKeyThrowsExceptionForInvalidFirstArgument()
    {
        $this->assertArrayHasKey(null, []);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertArrayHasKey
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertArrayHasKeyThrowsExceptionForInvalidSecondArgument()
    {
        $this->assertArrayHasKey(0, null);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertArrayHasKey
     */
    public function testAssertArrayHasIntegerKey()
    {
        $this->assertArrayHasKey(0, ['foo']);

        try {
            $this->assertArrayHasKey(1, ['foo']);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertArraySubset
     * @covers \PhpUnit\Framework\Constraint\ArraySubset
     */
    public function testassertArraySubset()
    {
        $array = [
            'a' => 'item a',
            'b' => 'item b',
            'c' => ['a2' => 'item a2', 'b2' => 'item b2'],
            'd' => ['a2' => ['a3' => 'item a3', 'b3' => 'item b3']]
        ];

        $this->assertArraySubset(['a' => 'item a', 'c' => ['a2' => 'item a2']], $array);
        $this->assertArraySubset(['a' => 'item a', 'd' => ['a2' => ['b3' => 'item b3']]], $array);

        try {
            $this->assertArraySubset(['a' => 'bad value'], $array);
        } catch (AssertionFailedError $e) {
        }

        try {
            $this->assertArraySubset(['d' => ['a2' => ['bad index' => 'item b3']]], $array);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertArraySubset
     * @covers \PhpUnit\Framework\Constraint\ArraySubset
     */
    public function testassertArraySubsetWithDeepNestedArrays()
    {
        $array = [
            'path' => [
                'to' => [
                    'the' => [
                        'cake' => 'is a lie'
                    ]
                ]
            ]
        ];

        $this->assertArraySubset(['path' => []], $array);
        $this->assertArraySubset(['path' => ['to' => []]], $array);
        $this->assertArraySubset(['path' => ['to' => ['the' => []]]], $array);
        $this->assertArraySubset(['path' => ['to' => ['the' => ['cake' => 'is a lie']]]], $array);

        try {
            $this->assertArraySubset(['path' => ['to' => ['the' => ['cake' => 'is not a lie']]]], $array);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertArraySubset
     * @covers \PhpUnit\Framework\Constraint\ArraySubset
     */
    public function testassertArraySubsetWithNoStrictCheckAndObjects()
    {
        $obj       = new \stdClass;
        $reference = &$obj;
        $array     = ['a' => $obj];

        $this->assertArraySubset(['a' => $reference], $array);
        $this->assertArraySubset(['a' => new \stdClass], $array);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertArraySubset
     * @covers \PhpUnit\Framework\Constraint\ArraySubset
     */
    public function testassertArraySubsetWithStrictCheckAndObjects()
    {
        $obj       = new \stdClass;
        $reference = &$obj;
        $array     = ['a' => $obj];

        $this->assertArraySubset(['a' => $reference], $array, true);

        try {
            $this->assertArraySubset(['a' => new \stdClass], $array, true);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail('Strict recursive array check fail.');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertArraySubset
     * @covers \PhpUnit\Framework\Constraint\ArraySubset
     * @expectedException \PhpUnit\Framework\Exception
     * @expectedExceptionMessage array or ArrayAccess
     * @dataProvider assertArraySubsetInvalidArgumentProvider
     */
    public function testassertArraySubsetRaisesExceptionForInvalidArguments($partial, $subject)
    {
        $this->assertArraySubset($partial, $subject);
    }

    public function assertArraySubsetInvalidArgumentProvider()
    {
        return [
            [false, []],
            [[], false],
        ];
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertArrayNotHasKey
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertArrayNotHasKeyThrowsExceptionForInvalidFirstArgument()
    {
        $this->assertArrayNotHasKey(null, []);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertArrayNotHasKey
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertArrayNotHasKeyThrowsExceptionForInvalidSecondArgument()
    {
        $this->assertArrayNotHasKey(0, null);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertArrayNotHasKey
     */
    public function testAssertArrayNotHasIntegerKey()
    {
        $this->assertArrayNotHasKey(1, ['foo']);

        try {
            $this->assertArrayNotHasKey(0, ['foo']);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertArrayHasKey
     */
    public function testAssertArrayHasStringKey()
    {
        $this->assertArrayHasKey('foo', ['foo' => 'bar']);

        try {
            $this->assertArrayHasKey('bar', ['foo' => 'bar']);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertArrayNotHasKey
     */
    public function testAssertArrayNotHasStringKey()
    {
        $this->assertArrayNotHasKey('bar', ['foo' => 'bar']);

        try {
            $this->assertArrayNotHasKey('foo', ['foo' => 'bar']);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertArrayHasKey
     */
    public function testAssertArrayHasKeyAcceptsArrayObjectValue()
    {
        $array        = new ArrayObject();
        $array['foo'] = 'bar';
        $this->assertArrayHasKey('foo', $array);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertArrayHasKey
     * @expectedException \PhpUnit\Framework\AssertionFailedError
     */
    public function testAssertArrayHasKeyProperlyFailsWithArrayObjectValue()
    {
        $array        = new ArrayObject();
        $array['bar'] = 'bar';
        $this->assertArrayHasKey('foo', $array);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertArrayHasKey
     */
    public function testAssertArrayHasKeyAcceptsArrayAccessValue()
    {
        $array        = new SampleArrayAccess();
        $array['foo'] = 'bar';
        $this->assertArrayHasKey('foo', $array);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertArrayHasKey
     * @expectedException \PhpUnit\Framework\AssertionFailedError
     */
    public function testAssertArrayHasKeyProperlyFailsWithArrayAccessValue()
    {
        $array        = new SampleArrayAccess();
        $array['bar'] = 'bar';
        $this->assertArrayHasKey('foo', $array);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertArrayNotHasKey
     */
    public function testAssertArrayNotHasKeyAcceptsArrayAccessValue()
    {
        $array        = new ArrayObject();
        $array['foo'] = 'bar';
        $this->assertArrayNotHasKey('bar', $array);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertArrayNotHasKey
     * @expectedException \PhpUnit\Framework\AssertionFailedError
     */
    public function testAssertArrayNotHasKeyPropertlyFailsWithArrayAccessValue()
    {
        $array        = new ArrayObject();
        $array['bar'] = 'bar';
        $this->assertArrayNotHasKey('bar', $array);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertContains
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertContainsThrowsException()
    {
        $this->assertContains(null, null);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertContains
     */
    public function testAssertIteratorContainsObject()
    {
        $foo = new stdClass;

        $this->assertContains($foo, new TestIterator([$foo]));

        try {
            $this->assertContains($foo, new TestIterator([new stdClass]));
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertContains
     */
    public function testAssertIteratorContainsString()
    {
        $this->assertContains('foo', new TestIterator(['foo']));

        try {
            $this->assertContains('foo', new TestIterator(['bar']));
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertContains
     */
    public function testAssertStringContainsString()
    {
        $this->assertContains('foo', 'foobar');

        try {
            $this->assertContains('foo', 'bar');
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertNotContains
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertNotContainsThrowsException()
    {
        $this->assertNotContains(null, null);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotContains
     */
    public function testAssertSplObjectStorageNotContainsObject()
    {
        $a = new stdClass;
        $b = new stdClass;
        $c = new SplObjectStorage;
        $c->attach($a);

        $this->assertNotContains($b, $c);

        try {
            $this->assertNotContains($a, $c);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotContains
     */
    public function testAssertArrayNotContainsObject()
    {
        $a = new stdClass;
        $b = new stdClass;

        $this->assertNotContains($a, [$b]);

        try {
            $this->assertNotContains($a, [$a]);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotContains
     */
    public function testAssertArrayNotContainsString()
    {
        $this->assertNotContains('foo', ['bar']);

        try {
            $this->assertNotContains('foo', ['foo']);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotContains
     */
    public function testAssertArrayNotContainsNonObject()
    {
        $this->assertNotContains('foo', [true], '', false, true, true);

        try {
            $this->assertNotContains('foo', [true]);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotContains
     */
    public function testAssertStringNotContainsString()
    {
        $this->assertNotContains('foo', 'bar');

        try {
            $this->assertNotContains('foo', 'foo');
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertContainsOnly
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertContainsOnlyThrowsException()
    {
        $this->assertContainsOnly(null, null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertNotContainsOnly
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertNotContainsOnlyThrowsException()
    {
        $this->assertNotContainsOnly(null, null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertContainsOnlyInstancesOf
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertContainsOnlyInstancesOfThrowsException()
    {
        $this->assertContainsOnlyInstancesOf(null, null);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertContainsOnly
     */
    public function testAssertArrayContainsOnlyIntegers()
    {
        $this->assertContainsOnly('integer', [1, 2, 3]);

        try {
            $this->assertContainsOnly('integer', ['1', 2, 3]);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotContainsOnly
     */
    public function testAssertArrayNotContainsOnlyIntegers()
    {
        $this->assertNotContainsOnly('integer', ['1', 2, 3]);

        try {
            $this->assertNotContainsOnly('integer', [1, 2, 3]);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertContainsOnly
     */
    public function testAssertArrayContainsOnlyStdClass()
    {
        $this->assertContainsOnly('StdClass', [new StdClass]);

        try {
            $this->assertContainsOnly('StdClass', ['StdClass']);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotContainsOnly
     */
    public function testAssertArrayNotContainsOnlyStdClass()
    {
        $this->assertNotContainsOnly('StdClass', ['StdClass']);

        try {
            $this->assertNotContainsOnly('StdClass', [new StdClass]);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    protected function createDOMDocument($content)
    {
        $document                     = new DOMDocument;
        $document->preserveWhiteSpace = false;
        $document->loadXML($content);

        return $document;
    }

    protected function sameValues()
    {
        $object = new SampleClass(4, 8, 15);
        // cannot use $filesDirectory, because neither setUp() nor
        // setUpBeforeClass() are executed before the data providers
        $file     = dirname(__DIR__) . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'foo.xml';
        $resource = fopen($file, 'r');

        return [
            // null
            [null, null],
            // strings
            ['a', 'a'],
            // integers
            [0, 0],
            // floats
            [2.3, 2.3],
            [1/3, 1 - 2/3],
            [log(0), log(0)],
            // arrays
            [[], []],
            [[0 => 1], [0 => 1]],
            [[0 => null], [0 => null]],
            [['a', 'b' => [1, 2]], ['a', 'b' => [1, 2]]],
            // objects
            [$object, $object],
            // resources
            [$resource, $resource],
        ];
    }

    protected function notEqualValues()
    {
        // cyclic dependencies
        $book1                  = new Book;
        $book1->author          = new Author('Terry Pratchett');
        $book1->author->books[] = $book1;
        $book2                  = new Book;
        $book2->author          = new Author('Terry Pratch');
        $book2->author->books[] = $book2;

        $book3         = new Book;
        $book3->author = 'Terry Pratchett';
        $book4         = new stdClass;
        $book4->author = 'Terry Pratchett';

        $object1  = new SampleClass(4, 8, 15);
        $object2  = new SampleClass(16, 23, 42);
        $object3  = new SampleClass(4, 8, 15);
        $storage1 = new SplObjectStorage;
        $storage1->attach($object1);
        $storage2 = new SplObjectStorage;
        $storage2->attach($object3); // same content, different object

        // cannot use $filesDirectory, because neither setUp() nor
        // setUpBeforeClass() are executed before the data providers
        $file = dirname(__DIR__) . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'foo.xml';

        return [
            // strings
            ['a', 'b'],
            ['a', 'A'],
            // https://github.com/sebastianbergmann/phpunit/issues/1023
            ['9E6666666','9E7777777'],
            // integers
            [1, 2],
            [2, 1],
            // floats
            [2.3, 4.2],
            [2.3, 4.2, 0.5],
            [[2.3], [4.2], 0.5],
            [[[2.3]], [[4.2]], 0.5],
            [new Struct(2.3), new Struct(4.2), 0.5],
            [[new Struct(2.3)], [new Struct(4.2)], 0.5],
            // NAN
            [NAN, NAN],
            // arrays
            [[], [0 => 1]],
            [[0 => 1], []],
            [[0 => null], []],
            [[0 => 1, 1 => 2], [0 => 1, 1 => 3]],
            [['a', 'b' => [1, 2]], ['a', 'b' => [2, 1]]],
            // objects
            [new SampleClass(4, 8, 15), new SampleClass(16, 23, 42)],
            [$object1, $object2],
            [$book1, $book2],
            [$book3, $book4], // same content, different class
            // resources
            [fopen($file, 'r'), fopen($file, 'r')],
            // SplObjectStorage
            [$storage1, $storage2],
            // DOMDocument
            [
                $this->createDOMDocument('<root></root>'),
                $this->createDOMDocument('<bar/>'),
            ],
            [
                $this->createDOMDocument('<foo attr1="bar"/>'),
                $this->createDOMDocument('<foo attr1="foobar"/>'),
            ],
            [
                $this->createDOMDocument('<foo> bar </foo>'),
                $this->createDOMDocument('<foo />'),
            ],
            [
                $this->createDOMDocument('<foo xmlns="urn:myns:bar"/>'),
                $this->createDOMDocument('<foo xmlns="urn:notmyns:bar"/>'),
            ],
            [
                $this->createDOMDocument('<foo> bar </foo>'),
                $this->createDOMDocument('<foo> bir </foo>'),
            ],
            [
                new DateTime('2013-03-29 04:13:35', new DateTimeZone('America/New_York')),
                new DateTime('2013-03-29 03:13:35', new DateTimeZone('America/New_York')),
            ],
            [
                new DateTime('2013-03-29 04:13:35', new DateTimeZone('America/New_York')),
                new DateTime('2013-03-29 03:13:35', new DateTimeZone('America/New_York')),
                3500
            ],
            [
                new DateTime('2013-03-29 04:13:35', new DateTimeZone('America/New_York')),
                new DateTime('2013-03-29 05:13:35', new DateTimeZone('America/New_York')),
                3500
            ],
            [
                new DateTime('2013-03-29', new DateTimeZone('America/New_York')),
                new DateTime('2013-03-30', new DateTimeZone('America/New_York')),
            ],
            [
                new DateTime('2013-03-29', new DateTimeZone('America/New_York')),
                new DateTime('2013-03-30', new DateTimeZone('America/New_York')),
                43200
            ],
            [
                new DateTime('2013-03-29 04:13:35', new DateTimeZone('America/New_York')),
                new DateTime('2013-03-29 04:13:35', new DateTimeZone('America/Chicago')),
            ],
            [
                new DateTime('2013-03-29 04:13:35', new DateTimeZone('America/New_York')),
                new DateTime('2013-03-29 04:13:35', new DateTimeZone('America/Chicago')),
                3500
            ],
            [
                new DateTime('2013-03-30', new DateTimeZone('America/New_York')),
                new DateTime('2013-03-30', new DateTimeZone('America/Chicago')),
            ],
            [
                new DateTime('2013-03-29T05:13:35-0600'),
                new DateTime('2013-03-29T04:13:35-0600'),
            ],
            [
                new DateTime('2013-03-29T05:13:35-0600'),
                new DateTime('2013-03-29T05:13:35-0500'),
            ],
            // Exception
            //array(new \Exception('Exception 1'), new \Exception('Exception 2')),
            // different types
            [new SampleClass(4, 8, 15), false],
            [false, new SampleClass(4, 8, 15)],
            [[0 => 1, 1 => 2], false],
            [false, [0 => 1, 1 => 2]],
            [[], new stdClass],
            [new stdClass, []],
            // PHP: 0 == 'Foobar' => true!
            // We want these values to differ
            [0, 'Foobar'],
            ['Foobar', 0],
            [3, acos(8)],
            [acos(8), 3]
        ];
    }

    protected function equalValues()
    {
        // cyclic dependencies
        $book1                  = new Book;
        $book1->author          = new Author('Terry Pratchett');
        $book1->author->books[] = $book1;
        $book2                  = new Book;
        $book2->author          = new Author('Terry Pratchett');
        $book2->author->books[] = $book2;

        $object1  = new SampleClass(4, 8, 15);
        $object2  = new SampleClass(4, 8, 15);
        $storage1 = new SplObjectStorage;
        $storage1->attach($object1);
        $storage2 = new SplObjectStorage;
        $storage2->attach($object1);

        return [
            // strings
            ['a', 'A', 0, false, true], // ignore case
            // arrays
            [['a' => 1, 'b' => 2], ['b' => 2, 'a' => 1]],
            [[1], ['1']],
            [[3, 2, 1], [2, 3, 1], 0, true], // canonicalized comparison
            // floats
            [2.3, 2.5, 0.5],
            [[2.3], [2.5], 0.5],
            [[[2.3]], [[2.5]], 0.5],
            [new Struct(2.3), new Struct(2.5), 0.5],
            [[new Struct(2.3)], [new Struct(2.5)], 0.5],
            // numeric with delta
            [1, 2, 1],
            // objects
            [$object1, $object2],
            [$book1, $book2],
            // SplObjectStorage
            [$storage1, $storage2],
            // DOMDocument
            [
                $this->createDOMDocument('<root></root>'),
                $this->createDOMDocument('<root/>'),
            ],
            [
                $this->createDOMDocument('<root attr="bar"></root>'),
                $this->createDOMDocument('<root attr="bar"/>'),
            ],
            [
                $this->createDOMDocument('<root><foo attr="bar"></foo></root>'),
                $this->createDOMDocument('<root><foo attr="bar"/></root>'),
            ],
            [
                $this->createDOMDocument("<root>\n  <child/>\n</root>"),
                $this->createDOMDocument('<root><child/></root>'),
            ],
            [
                new DateTime('2013-03-29 04:13:35', new DateTimeZone('America/New_York')),
                new DateTime('2013-03-29 04:13:35', new DateTimeZone('America/New_York')),
            ],
            [
                new DateTime('2013-03-29 04:13:35', new DateTimeZone('America/New_York')),
                new DateTime('2013-03-29 04:13:25', new DateTimeZone('America/New_York')),
                10
            ],
            [
                new DateTime('2013-03-29 04:13:35', new DateTimeZone('America/New_York')),
                new DateTime('2013-03-29 04:14:40', new DateTimeZone('America/New_York')),
                65
            ],
            [
                new DateTime('2013-03-29', new DateTimeZone('America/New_York')),
                new DateTime('2013-03-29', new DateTimeZone('America/New_York')),
            ],
            [
                new DateTime('2013-03-29 04:13:35', new DateTimeZone('America/New_York')),
                new DateTime('2013-03-29 03:13:35', new DateTimeZone('America/Chicago')),
            ],
            [
                new DateTime('2013-03-29 04:13:35', new DateTimeZone('America/New_York')),
                new DateTime('2013-03-29 03:13:49', new DateTimeZone('America/Chicago')),
                15
            ],
            [
                new DateTime('2013-03-30', new DateTimeZone('America/New_York')),
                new DateTime('2013-03-29 23:00:00', new DateTimeZone('America/Chicago')),
            ],
            [
                new DateTime('2013-03-30', new DateTimeZone('America/New_York')),
                new DateTime('2013-03-29 23:01:30', new DateTimeZone('America/Chicago')),
                100
            ],
            [
                new DateTime('@1364616000'),
                new DateTime('2013-03-29 23:00:00', new DateTimeZone('America/Chicago')),
            ],
            [
                new DateTime('2013-03-29T05:13:35-0500'),
                new DateTime('2013-03-29T04:13:35-0600'),
            ],
            // Exception
            //array(new \Exception('Exception 1'), new \Exception('Exception 1')),
            // mixed types
            [0, '0'],
            ['0', 0],
            [2.3, '2.3'],
            ['2.3', 2.3],
            [(string) (1/3), 1 - 2/3],
            [1/3, (string) (1 - 2/3)],
            ['string representation', new ClassWithToString],
            [new ClassWithToString, 'string representation'],
        ];
    }

    public function equalProvider()
    {
        // same |= equal
        return array_merge($this->equalValues(), $this->sameValues());
    }

    public function notEqualProvider()
    {
        return $this->notEqualValues();
    }

    public function sameProvider()
    {
        return $this->sameValues();
    }

    public function notSameProvider()
    {
        // not equal |= not same
        // equal, ¬same |= not same
        return array_merge($this->notEqualValues(), $this->equalValues());
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertEquals
     * @dataProvider equalProvider
     */
    public function testAssertEqualsSucceeds($a, $b, $delta = 0.0, $canonicalize = false, $ignoreCase = false)
    {
        $this->assertEquals($a, $b, '', $delta, 10, $canonicalize, $ignoreCase);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertEquals
     * @dataProvider notEqualProvider
     */
    public function testAssertEqualsFails($a, $b, $delta = 0.0, $canonicalize = false, $ignoreCase = false)
    {
        try {
            $this->assertEquals($a, $b, '', $delta, 10, $canonicalize, $ignoreCase);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotEquals
     * @dataProvider notEqualProvider
     */
    public function testAssertNotEqualsSucceeds($a, $b, $delta = 0.0, $canonicalize = false, $ignoreCase = false)
    {
        $this->assertNotEquals($a, $b, '', $delta, 10, $canonicalize, $ignoreCase);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotEquals
     * @dataProvider equalProvider
     */
    public function testAssertNotEqualsFails($a, $b, $delta = 0.0, $canonicalize = false, $ignoreCase = false)
    {
        try {
            $this->assertNotEquals($a, $b, '', $delta, 10, $canonicalize, $ignoreCase);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertSame
     * @dataProvider sameProvider
     */
    public function testAssertSameSucceeds($a, $b)
    {
        $this->assertSame($a, $b);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertSame
     * @dataProvider notSameProvider
     */
    public function testAssertSameFails($a, $b)
    {
        try {
            $this->assertSame($a, $b);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotSame
     * @dataProvider notSameProvider
     */
    public function testAssertNotSameSucceeds($a, $b)
    {
        $this->assertNotSame($a, $b);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotSame
     * @dataProvider sameProvider
     */
    public function testAssertNotSameFails($a, $b)
    {
        try {
            $this->assertNotSame($a, $b);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertXmlFileEqualsXmlFile
     */
    public function testAssertXmlFileEqualsXmlFile()
    {
        $this->assertXmlFileEqualsXmlFile(
            $this->filesDirectory . 'foo.xml',
            $this->filesDirectory . 'foo.xml'
        );

        try {
            $this->assertXmlFileEqualsXmlFile(
                $this->filesDirectory . 'foo.xml',
                $this->filesDirectory . 'bar.xml'
            );
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertXmlFileNotEqualsXmlFile
     */
    public function testAssertXmlFileNotEqualsXmlFile()
    {
        $this->assertXmlFileNotEqualsXmlFile(
            $this->filesDirectory . 'foo.xml',
            $this->filesDirectory . 'bar.xml'
        );

        try {
            $this->assertXmlFileNotEqualsXmlFile(
                $this->filesDirectory . 'foo.xml',
                $this->filesDirectory . 'foo.xml'
            );
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertXmlStringEqualsXmlFile
     */
    public function testAssertXmlStringEqualsXmlFile()
    {
        $this->assertXmlStringEqualsXmlFile(
            $this->filesDirectory . 'foo.xml',
            file_get_contents($this->filesDirectory . 'foo.xml')
        );

        try {
            $this->assertXmlStringEqualsXmlFile(
                $this->filesDirectory . 'foo.xml',
                file_get_contents($this->filesDirectory . 'bar.xml')
            );
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertXmlStringNotEqualsXmlFile
     */
    public function testXmlStringNotEqualsXmlFile()
    {
        $this->assertXmlStringNotEqualsXmlFile(
            $this->filesDirectory . 'foo.xml',
            file_get_contents($this->filesDirectory . 'bar.xml')
        );

        try {
            $this->assertXmlStringNotEqualsXmlFile(
                $this->filesDirectory . 'foo.xml',
                file_get_contents($this->filesDirectory . 'foo.xml')
            );
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertXmlStringEqualsXmlString
     */
    public function testAssertXmlStringEqualsXmlString()
    {
        $this->assertXmlStringEqualsXmlString('<root/>', '<root/>');

        try {
            $this->assertXmlStringEqualsXmlString('<foo/>', '<bar/>');
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertXmlStringNotEqualsXmlString
     */
    public function testAssertXmlStringNotEqualsXmlString()
    {
        $this->assertXmlStringNotEqualsXmlString('<foo/>', '<bar/>');

        try {
            $this->assertXmlStringNotEqualsXmlString('<root/>', '<root/>');
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertEqualXMLStructure
     */
    public function testXMLStructureIsSame()
    {
        $expected = new DOMDocument;
        $expected->load($this->filesDirectory . 'structureExpected.xml');

        $actual = new DOMDocument;
        $actual->load($this->filesDirectory . 'structureExpected.xml');

        $this->assertEqualXMLStructure(
            $expected->firstChild, $actual->firstChild, true
        );
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertEqualXMLStructure
     * @expectedException \PhpUnit\Framework\ExpectationFailedException
     */
    public function testXMLStructureWrongNumberOfAttributes()
    {
        $expected = new DOMDocument;
        $expected->load($this->filesDirectory . 'structureExpected.xml');

        $actual = new DOMDocument;
        $actual->load($this->filesDirectory . 'structureWrongNumberOfAttributes.xml');

        $this->assertEqualXMLStructure(
            $expected->firstChild, $actual->firstChild, true
        );
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertEqualXMLStructure
     * @expectedException \PhpUnit\Framework\ExpectationFailedException
     */
    public function testXMLStructureWrongNumberOfNodes()
    {
        $expected = new DOMDocument;
        $expected->load($this->filesDirectory . 'structureExpected.xml');

        $actual = new DOMDocument;
        $actual->load($this->filesDirectory . 'structureWrongNumberOfNodes.xml');

        $this->assertEqualXMLStructure(
            $expected->firstChild, $actual->firstChild, true
        );
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertEqualXMLStructure
     */
    public function testXMLStructureIsSameButDataIsNot()
    {
        $expected = new DOMDocument;
        $expected->load($this->filesDirectory . 'structureExpected.xml');

        $actual = new DOMDocument;
        $actual->load($this->filesDirectory . 'structureIsSameButDataIsNot.xml');

        $this->assertEqualXMLStructure(
            $expected->firstChild, $actual->firstChild, true
        );
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertEqualXMLStructure
     */
    public function testXMLStructureAttributesAreSameButValuesAreNot()
    {
        $expected = new DOMDocument;
        $expected->load($this->filesDirectory . 'structureExpected.xml');

        $actual = new DOMDocument;
        $actual->load($this->filesDirectory . 'structureAttributesAreSameButValuesAreNot.xml');

        $this->assertEqualXMLStructure(
            $expected->firstChild, $actual->firstChild, true
        );
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertEqualXMLStructure
     */
    public function testXMLStructureIgnoreTextNodes()
    {
        $expected = new DOMDocument;
        $expected->load($this->filesDirectory . 'structureExpected.xml');

        $actual = new DOMDocument;
        $actual->load($this->filesDirectory . 'structureIgnoreTextNodes.xml');

        $this->assertEqualXMLStructure(
            $expected->firstChild, $actual->firstChild, true
        );
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertEquals
     */
    public function testAssertStringEqualsNumeric()
    {
        $this->assertEquals('0', 0);

        try {
            $this->assertEquals('0', 1);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotEquals
     */
    public function testAssertStringEqualsNumeric2()
    {
        $this->assertNotEquals('A', 0);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertFileExists
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertFileExistsThrowsException()
    {
        $this->assertFileExists(null);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertFileExists
     */
    public function testAssertFileExists()
    {
        $this->assertFileExists(__FILE__);

        try {
            $this->assertFileExists(__DIR__ . DIRECTORY_SEPARATOR . 'NotExisting');
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertFileNotExists
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertFileNotExistsThrowsException()
    {
        $this->assertFileNotExists(null);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertFileNotExists
     */
    public function testAssertFileNotExists()
    {
        $this->assertFileNotExists(__DIR__ . DIRECTORY_SEPARATOR . 'NotExisting');

        try {
            $this->assertFileNotExists(__FILE__);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertObjectHasAttribute
     */
    public function testAssertObjectHasAttribute()
    {
        $o = new Author('Terry Pratchett');

        $this->assertObjectHasAttribute('name', $o);

        try {
            $this->assertObjectHasAttribute('foo', $o);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertObjectNotHasAttribute
     */
    public function testAssertObjectNotHasAttribute()
    {
        $o = new Author('Terry Pratchett');

        $this->assertObjectNotHasAttribute('foo', $o);

        try {
            $this->assertObjectNotHasAttribute('name', $o);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertFinite
     */
    public function testAssertFinite()
    {
        $this->assertFinite(1);

        try {
            $this->assertFinite(INF);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertInfinite
     */
    public function testAssertInfinite()
    {
        $this->assertInfinite(INF);

        try {
            $this->assertInfinite(1);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNan
     */
    public function testAssertNan()
    {
        $this->assertNan(NAN);

        try {
            $this->assertNan(1);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNull
     */
    public function testAssertNull()
    {
        $this->assertNull(null);

        try {
            $this->assertNull(new stdClass);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotNull
     */
    public function testAssertNotNull()
    {
        $this->assertNotNull(new stdClass);

        try {
            $this->assertNotNull(null);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertTrue
     */
    public function testAssertTrue()
    {
        $this->assertTrue(true);

        try {
            $this->assertTrue(false);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotTrue
     */
    public function testAssertNotTrue()
    {
        $this->assertNotTrue(false);
        $this->assertNotTrue(1);
        $this->assertNotTrue('true');

        try {
            $this->assertNotTrue(true);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertFalse
     */
    public function testAssertFalse()
    {
        $this->assertFalse(false);

        try {
            $this->assertFalse(true);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotFalse
     */
    public function testAssertNotFalse()
    {
        $this->assertNotFalse(true);
        $this->assertNotFalse(0);
        $this->assertNotFalse('');

        try {
            $this->assertNotFalse(false);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertRegExp
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertRegExpThrowsException()
    {
        $this->assertRegExp(null, null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertRegExp
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertRegExpThrowsException2()
    {
        $this->assertRegExp('', null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertNotRegExp
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertNotRegExpThrowsException()
    {
        $this->assertNotRegExp(null, null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertNotRegExp
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertNotRegExpThrowsException2()
    {
        $this->assertNotRegExp('', null);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertRegExp
     */
    public function testAssertRegExp()
    {
        $this->assertRegExp('/foo/', 'foobar');

        try {
            $this->assertRegExp('/foo/', 'bar');
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotRegExp
     */
    public function testAssertNotRegExp()
    {
        $this->assertNotRegExp('/foo/', 'bar');

        try {
            $this->assertNotRegExp('/foo/', 'foobar');
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertSame
     */
    public function testAssertSame()
    {
        $o = new stdClass;

        $this->assertSame($o, $o);

        try {
            $this->assertSame(
                new stdClass,
                new stdClass
            );
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertSame
     */
    public function testAssertSame2()
    {
        $this->assertSame(true, true);
        $this->assertSame(false, false);

        try {
            $this->assertSame(true, false);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotSame
     */
    public function testAssertNotSame()
    {
        $this->assertNotSame(
            new stdClass,
            null
        );

        $this->assertNotSame(
            null,
            new stdClass
        );

        $this->assertNotSame(
            new stdClass,
            new stdClass
        );

        $o = new stdClass;

        try {
            $this->assertNotSame($o, $o);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotSame
     */
    public function testAssertNotSame2()
    {
        $this->assertNotSame(true, false);
        $this->assertNotSame(false, true);

        try {
            $this->assertNotSame(true, true);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotSame
     */
    public function testAssertNotSameFailsNull()
    {
        try {
            $this->assertNotSame(null, null);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertGreaterThan
     */
    public function testGreaterThan()
    {
        $this->assertGreaterThan(1, 2);

        try {
            $this->assertGreaterThan(2, 1);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeGreaterThan
     */
    public function testAttributeGreaterThan()
    {
        $this->assertAttributeGreaterThan(
            1, 'bar', new ClassWithNonPublicAttributes
        );

        try {
            $this->assertAttributeGreaterThan(
                1, 'foo', new ClassWithNonPublicAttributes
            );
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertGreaterThanOrEqual
     */
    public function testGreaterThanOrEqual()
    {
        $this->assertGreaterThanOrEqual(1, 2);

        try {
            $this->assertGreaterThanOrEqual(2, 1);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeGreaterThanOrEqual
     */
    public function testAttributeGreaterThanOrEqual()
    {
        $this->assertAttributeGreaterThanOrEqual(
            1, 'bar', new ClassWithNonPublicAttributes
        );

        try {
            $this->assertAttributeGreaterThanOrEqual(
                2, 'foo', new ClassWithNonPublicAttributes
            );
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertLessThan
     */
    public function testLessThan()
    {
        $this->assertLessThan(2, 1);

        try {
            $this->assertLessThan(1, 2);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeLessThan
     */
    public function testAttributeLessThan()
    {
        $this->assertAttributeLessThan(
            2, 'foo', new ClassWithNonPublicAttributes
        );

        try {
            $this->assertAttributeLessThan(
                1, 'bar', new ClassWithNonPublicAttributes
            );
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertLessThanOrEqual
     */
    public function testLessThanOrEqual()
    {
        $this->assertLessThanOrEqual(2, 1);

        try {
            $this->assertLessThanOrEqual(1, 2);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeLessThanOrEqual
     */
    public function testAttributeLessThanOrEqual()
    {
        $this->assertAttributeLessThanOrEqual(
            2, 'foo', new ClassWithNonPublicAttributes
        );

        try {
            $this->assertAttributeLessThanOrEqual(
                1, 'bar', new ClassWithNonPublicAttributes
            );
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::readAttribute
     * @covers \PhpUnit\Framework\Assert::getStaticAttribute
     * @covers \PhpUnit\Framework\Assert::getObjectAttribute
     */
    public function testReadAttribute()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertEquals('foo', $this->readAttribute($obj, 'publicAttribute'));
        $this->assertEquals('bar', $this->readAttribute($obj, 'protectedAttribute'));
        $this->assertEquals('baz', $this->readAttribute($obj, 'privateAttribute'));
        $this->assertEquals('bar', $this->readAttribute($obj, 'protectedParentAttribute'));
        //$this->assertEquals('bar', $this->readAttribute($obj, 'privateParentAttribute'));
    }

    /**
     * @covers \PhpUnit\Framework\Assert::readAttribute
     * @covers \PhpUnit\Framework\Assert::getStaticAttribute
     * @covers \PhpUnit\Framework\Assert::getObjectAttribute
     */
    public function testReadAttribute2()
    {
        $this->assertEquals('foo', $this->readAttribute('ClassWithNonPublicAttributes', 'publicStaticAttribute'));
        $this->assertEquals('bar', $this->readAttribute('ClassWithNonPublicAttributes', 'protectedStaticAttribute'));
        $this->assertEquals('baz', $this->readAttribute('ClassWithNonPublicAttributes', 'privateStaticAttribute'));
        $this->assertEquals('foo', $this->readAttribute('ClassWithNonPublicAttributes', 'protectedStaticParentAttribute'));
        $this->assertEquals('foo', $this->readAttribute('ClassWithNonPublicAttributes', 'privateStaticParentAttribute'));
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::readAttribute
     * @covers            \PhpUnit\Framework\Assert::getStaticAttribute
     * @covers            \PhpUnit\Framework\Assert::getObjectAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testReadAttribute3()
    {
        $this->readAttribute('StdClass', null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::readAttribute
     * @covers            \PhpUnit\Framework\Assert::getStaticAttribute
     * @covers            \PhpUnit\Framework\Assert::getObjectAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testReadAttribute4()
    {
        $this->readAttribute('NotExistingClass', 'foo');
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::readAttribute
     * @covers            \PhpUnit\Framework\Assert::getStaticAttribute
     * @covers            \PhpUnit\Framework\Assert::getObjectAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testReadAttribute5()
    {
        $this->readAttribute(null, 'foo');
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::readAttribute
     * @covers            \PhpUnit\Framework\Assert::getStaticAttribute
     * @covers            \PhpUnit\Framework\Assert::getObjectAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testReadAttributeIfAttributeNameIsNotValid()
    {
        $this->readAttribute('StdClass', '2');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::getStaticAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testGetStaticAttributeRaisesExceptionForInvalidFirstArgument()
    {
        $this->getStaticAttribute(null, 'foo');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::getStaticAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testGetStaticAttributeRaisesExceptionForInvalidFirstArgument2()
    {
        $this->getStaticAttribute('NotExistingClass', 'foo');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::getStaticAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testGetStaticAttributeRaisesExceptionForInvalidSecondArgument()
    {
        $this->getStaticAttribute('stdClass', null);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::getStaticAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testGetStaticAttributeRaisesExceptionForInvalidSecondArgument2()
    {
        $this->getStaticAttribute('stdClass', '0');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::getStaticAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testGetStaticAttributeRaisesExceptionForInvalidSecondArgument3()
    {
        $this->getStaticAttribute('stdClass', 'foo');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::getObjectAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testGetObjectAttributeRaisesExceptionForInvalidFirstArgument()
    {
        $this->getObjectAttribute(null, 'foo');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::getObjectAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testGetObjectAttributeRaisesExceptionForInvalidSecondArgument()
    {
        $this->getObjectAttribute(new stdClass, null);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::getObjectAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testGetObjectAttributeRaisesExceptionForInvalidSecondArgument2()
    {
        $this->getObjectAttribute(new stdClass, '0');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::getObjectAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testGetObjectAttributeRaisesExceptionForInvalidSecondArgument3()
    {
        $this->getObjectAttribute(new stdClass, 'foo');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::getObjectAttribute
     */
    public function testGetObjectAttributeWorksForInheritedAttributes()
    {
        $this->assertEquals(
            'bar',
            $this->getObjectAttribute(new ClassWithNonPublicAttributes, 'privateParentAttribute')
        );
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeContains
     */
    public function testAssertPublicAttributeContains()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertAttributeContains('foo', 'publicArray', $obj);

        try {
            $this->assertAttributeContains('bar', 'publicArray', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeContainsOnly
     */
    public function testAssertPublicAttributeContainsOnly()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertAttributeContainsOnly('string', 'publicArray', $obj);

        try {
            $this->assertAttributeContainsOnly('integer', 'publicArray', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeNotContains
     */
    public function testAssertPublicAttributeNotContains()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertAttributeNotContains('bar', 'publicArray', $obj);

        try {
            $this->assertAttributeNotContains('foo', 'publicArray', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeNotContainsOnly
     */
    public function testAssertPublicAttributeNotContainsOnly()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertAttributeNotContainsOnly('integer', 'publicArray', $obj);

        try {
            $this->assertAttributeNotContainsOnly('string', 'publicArray', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeContains
     */
    public function testAssertProtectedAttributeContains()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertAttributeContains('bar', 'protectedArray', $obj);

        try {
            $this->assertAttributeContains('foo', 'protectedArray', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeNotContains
     */
    public function testAssertProtectedAttributeNotContains()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertAttributeNotContains('foo', 'protectedArray', $obj);

        try {
            $this->assertAttributeNotContains('bar', 'protectedArray', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeContains
     */
    public function testAssertPrivateAttributeContains()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertAttributeContains('baz', 'privateArray', $obj);

        try {
            $this->assertAttributeContains('foo', 'privateArray', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeNotContains
     */
    public function testAssertPrivateAttributeNotContains()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertAttributeNotContains('foo', 'privateArray', $obj);

        try {
            $this->assertAttributeNotContains('baz', 'privateArray', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeContains
     */
    public function testAssertAttributeContainsNonObject()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertAttributeContains(true, 'privateArray', $obj);

        try {
            $this->assertAttributeContains(true, 'privateArray', $obj, '', false, true, true);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeNotContains
     */
    public function testAssertAttributeNotContainsNonObject()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertAttributeNotContains(true, 'privateArray', $obj, '', false, true, true);

        try {
            $this->assertAttributeNotContains(true, 'privateArray', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeEquals
     */
    public function testAssertPublicAttributeEquals()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertAttributeEquals('foo', 'publicAttribute', $obj);

        try {
            $this->assertAttributeEquals('bar', 'publicAttribute', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeNotEquals
     */
    public function testAssertPublicAttributeNotEquals()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertAttributeNotEquals('bar', 'publicAttribute', $obj);

        try {
            $this->assertAttributeNotEquals('foo', 'publicAttribute', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeSame
     */
    public function testAssertPublicAttributeSame()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertAttributeSame('foo', 'publicAttribute', $obj);

        try {
            $this->assertAttributeSame('bar', 'publicAttribute', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeNotSame
     */
    public function testAssertPublicAttributeNotSame()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertAttributeNotSame('bar', 'publicAttribute', $obj);

        try {
            $this->assertAttributeNotSame('foo', 'publicAttribute', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeEquals
     */
    public function testAssertProtectedAttributeEquals()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertAttributeEquals('bar', 'protectedAttribute', $obj);

        try {
            $this->assertAttributeEquals('foo', 'protectedAttribute', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeNotEquals
     */
    public function testAssertProtectedAttributeNotEquals()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertAttributeNotEquals('foo', 'protectedAttribute', $obj);

        try {
            $this->assertAttributeNotEquals('bar', 'protectedAttribute', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeEquals
     */
    public function testAssertPrivateAttributeEquals()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertAttributeEquals('baz', 'privateAttribute', $obj);

        try {
            $this->assertAttributeEquals('foo', 'privateAttribute', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeNotEquals
     */
    public function testAssertPrivateAttributeNotEquals()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertAttributeNotEquals('foo', 'privateAttribute', $obj);

        try {
            $this->assertAttributeNotEquals('baz', 'privateAttribute', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeEquals
     */
    public function testAssertPublicStaticAttributeEquals()
    {
        $this->assertAttributeEquals('foo', 'publicStaticAttribute', 'ClassWithNonPublicAttributes');

        try {
            $this->assertAttributeEquals('bar', 'publicStaticAttribute', 'ClassWithNonPublicAttributes');
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeNotEquals
     */
    public function testAssertPublicStaticAttributeNotEquals()
    {
        $this->assertAttributeNotEquals('bar', 'publicStaticAttribute', 'ClassWithNonPublicAttributes');

        try {
            $this->assertAttributeNotEquals('foo', 'publicStaticAttribute', 'ClassWithNonPublicAttributes');
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeEquals
     */
    public function testAssertProtectedStaticAttributeEquals()
    {
        $this->assertAttributeEquals('bar', 'protectedStaticAttribute', 'ClassWithNonPublicAttributes');

        try {
            $this->assertAttributeEquals('foo', 'protectedStaticAttribute', 'ClassWithNonPublicAttributes');
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeNotEquals
     */
    public function testAssertProtectedStaticAttributeNotEquals()
    {
        $this->assertAttributeNotEquals('foo', 'protectedStaticAttribute', 'ClassWithNonPublicAttributes');

        try {
            $this->assertAttributeNotEquals('bar', 'protectedStaticAttribute', 'ClassWithNonPublicAttributes');
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeEquals
     */
    public function testAssertPrivateStaticAttributeEquals()
    {
        $this->assertAttributeEquals('baz', 'privateStaticAttribute', 'ClassWithNonPublicAttributes');

        try {
            $this->assertAttributeEquals('foo', 'privateStaticAttribute', 'ClassWithNonPublicAttributes');
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeNotEquals
     */
    public function testAssertPrivateStaticAttributeNotEquals()
    {
        $this->assertAttributeNotEquals('foo', 'privateStaticAttribute', 'ClassWithNonPublicAttributes');

        try {
            $this->assertAttributeNotEquals('baz', 'privateStaticAttribute', 'ClassWithNonPublicAttributes');
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertClassHasAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertClassHasAttributeThrowsException()
    {
        $this->assertClassHasAttribute(null, null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertClassHasAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertClassHasAttributeThrowsException2()
    {
        $this->assertClassHasAttribute('foo', null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertClassHasAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertClassHasAttributeThrowsExceptionIfAttributeNameIsNotValid()
    {
        $this->assertClassHasAttribute('1', 'ClassWithNonPublicAttributes');
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertClassNotHasAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertClassNotHasAttributeThrowsException()
    {
        $this->assertClassNotHasAttribute(null, null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertClassNotHasAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertClassNotHasAttributeThrowsException2()
    {
        $this->assertClassNotHasAttribute('foo', null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertClassNotHasAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertClassNotHasAttributeThrowsExceptionIfAttributeNameIsNotValid()
    {
        $this->assertClassNotHasAttribute('1', 'ClassWithNonPublicAttributes');
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertClassHasStaticAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertClassHasStaticAttributeThrowsException()
    {
        $this->assertClassHasStaticAttribute(null, null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertClassHasStaticAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertClassHasStaticAttributeThrowsException2()
    {
        $this->assertClassHasStaticAttribute('foo', null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertClassHasStaticAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertClassHasStaticAttributeThrowsExceptionIfAttributeNameIsNotValid()
    {
        $this->assertClassHasStaticAttribute('1', 'ClassWithNonPublicAttributes');
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertClassNotHasStaticAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertClassNotHasStaticAttributeThrowsException()
    {
        $this->assertClassNotHasStaticAttribute(null, null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertClassNotHasStaticAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertClassNotHasStaticAttributeThrowsException2()
    {
        $this->assertClassNotHasStaticAttribute('foo', null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertClassNotHasStaticAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertClassNotHasStaticAttributeThrowsExceptionIfAttributeNameIsNotValid()
    {
        $this->assertClassNotHasStaticAttribute('1', 'ClassWithNonPublicAttributes');
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertObjectHasAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertObjectHasAttributeThrowsException()
    {
        $this->assertObjectHasAttribute(null, null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertObjectHasAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertObjectHasAttributeThrowsException2()
    {
        $this->assertObjectHasAttribute('foo', null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertObjectHasAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertObjectHasAttributeThrowsExceptionIfAttributeNameIsNotValid()
    {
        $this->assertObjectHasAttribute('1', 'ClassWithNonPublicAttributes');
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertObjectNotHasAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertObjectNotHasAttributeThrowsException()
    {
        $this->assertObjectNotHasAttribute(null, null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertObjectNotHasAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertObjectNotHasAttributeThrowsException2()
    {
        $this->assertObjectNotHasAttribute('foo', null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertObjectNotHasAttribute
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertObjectNotHasAttributeThrowsExceptionIfAttributeNameIsNotValid()
    {
        $this->assertObjectNotHasAttribute('1', 'ClassWithNonPublicAttributes');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertClassHasAttribute
     */
    public function testClassHasPublicAttribute()
    {
        $this->assertClassHasAttribute('publicAttribute', 'ClassWithNonPublicAttributes');

        try {
            $this->assertClassHasAttribute('attribute', 'ClassWithNonPublicAttributes');
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertClassNotHasAttribute
     */
    public function testClassNotHasPublicAttribute()
    {
        $this->assertClassNotHasAttribute('attribute', 'ClassWithNonPublicAttributes');

        try {
            $this->assertClassNotHasAttribute('publicAttribute', 'ClassWithNonPublicAttributes');
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertClassHasStaticAttribute
     */
    public function testClassHasPublicStaticAttribute()
    {
        $this->assertClassHasStaticAttribute('publicStaticAttribute', 'ClassWithNonPublicAttributes');

        try {
            $this->assertClassHasStaticAttribute('attribute', 'ClassWithNonPublicAttributes');
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertClassNotHasStaticAttribute
     */
    public function testClassNotHasPublicStaticAttribute()
    {
        $this->assertClassNotHasStaticAttribute('attribute', 'ClassWithNonPublicAttributes');

        try {
            $this->assertClassNotHasStaticAttribute('publicStaticAttribute', 'ClassWithNonPublicAttributes');
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertObjectHasAttribute
     */
    public function testObjectHasPublicAttribute()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertObjectHasAttribute('publicAttribute', $obj);

        try {
            $this->assertObjectHasAttribute('attribute', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertObjectNotHasAttribute
     */
    public function testObjectNotHasPublicAttribute()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertObjectNotHasAttribute('attribute', $obj);

        try {
            $this->assertObjectNotHasAttribute('publicAttribute', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertObjectHasAttribute
     */
    public function testObjectHasOnTheFlyAttribute()
    {
        $obj      = new StdClass;
        $obj->foo = 'bar';

        $this->assertObjectHasAttribute('foo', $obj);

        try {
            $this->assertObjectHasAttribute('bar', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertObjectNotHasAttribute
     */
    public function testObjectNotHasOnTheFlyAttribute()
    {
        $obj      = new StdClass;
        $obj->foo = 'bar';

        $this->assertObjectNotHasAttribute('bar', $obj);

        try {
            $this->assertObjectNotHasAttribute('foo', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertObjectHasAttribute
     */
    public function testObjectHasProtectedAttribute()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertObjectHasAttribute('protectedAttribute', $obj);

        try {
            $this->assertObjectHasAttribute('attribute', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertObjectNotHasAttribute
     */
    public function testObjectNotHasProtectedAttribute()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertObjectNotHasAttribute('attribute', $obj);

        try {
            $this->assertObjectNotHasAttribute('protectedAttribute', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertObjectHasAttribute
     */
    public function testObjectHasPrivateAttribute()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertObjectHasAttribute('privateAttribute', $obj);

        try {
            $this->assertObjectHasAttribute('attribute', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertObjectNotHasAttribute
     */
    public function testObjectNotHasPrivateAttribute()
    {
        $obj = new ClassWithNonPublicAttributes;

        $this->assertObjectNotHasAttribute('attribute', $obj);

        try {
            $this->assertObjectNotHasAttribute('privateAttribute', $obj);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::attribute
     * @covers \PhpUnit\Framework\Assert::equalTo
     */
    public function testAssertThatAttributeEquals()
    {
        $this->assertThat(
            new ClassWithNonPublicAttributes,
            $this->attribute(
                $this->equalTo('foo'),
                'publicAttribute'
            )
        );
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertThat
     * @covers            \PhpUnit\Framework\Assert::attribute
     * @covers            \PhpUnit\Framework\Assert::equalTo
     * @expectedException \PhpUnit\Framework\AssertionFailedError
     */
    public function testAssertThatAttributeEquals2()
    {
        $this->assertThat(
            new ClassWithNonPublicAttributes,
            $this->attribute(
                $this->equalTo('bar'),
                'publicAttribute'
            )
        );
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::attribute
     * @covers \PhpUnit\Framework\Assert::equalTo
     */
    public function testAssertThatAttributeEqualTo()
    {
        $this->assertThat(
            new ClassWithNonPublicAttributes,
            $this->attributeEqualTo('publicAttribute', 'foo')
        );
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::anything
     */
    public function testAssertThatAnything()
    {
        $this->assertThat('anything', $this->anything());
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::isTrue
     */
    public function testAssertThatIsTrue()
    {
        $this->assertThat(true, $this->isTrue());
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::isFalse
     */
    public function testAssertThatIsFalse()
    {
        $this->assertThat(false, $this->isFalse());
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::isJson
     */
    public function testAssertThatIsJson()
    {
        $this->assertThat('{}', $this->isJson());
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::anything
     * @covers \PhpUnit\Framework\Assert::logicalAnd
     */
    public function testAssertThatAnythingAndAnything()
    {
        $this->assertThat(
            'anything',
            $this->logicalAnd(
                $this->anything(), $this->anything()
            )
        );
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::anything
     * @covers \PhpUnit\Framework\Assert::logicalOr
     */
    public function testAssertThatAnythingOrAnything()
    {
        $this->assertThat(
            'anything',
            $this->logicalOr(
                $this->anything(), $this->anything()
            )
        );
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::anything
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Assert::logicalXor
     */
    public function testAssertThatAnythingXorNotAnything()
    {
        $this->assertThat(
            'anything',
            $this->logicalXor(
                $this->anything(),
                $this->logicalNot($this->anything())
            )
        );
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::contains
     */
    public function testAssertThatContains()
    {
        $this->assertThat(['foo'], $this->contains('foo'));
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::stringContains
     */
    public function testAssertThatStringContains()
    {
        $this->assertThat('barfoobar', $this->stringContains('foo'));
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::containsOnly
     */
    public function testAssertThatContainsOnly()
    {
        $this->assertThat(['foo'], $this->containsOnly('string'));
    }
    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::containsOnlyInstancesOf
     */
    public function testAssertThatContainsOnlyInstancesOf()
    {
        $this->assertThat([new Book], $this->containsOnlyInstancesOf('Book'));
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::arrayHasKey
     */
    public function testAssertThatArrayHasKey()
    {
        $this->assertThat(['foo' => 'bar'], $this->arrayHasKey('foo'));
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::classHasAttribute
     */
    public function testAssertThatClassHasAttribute()
    {
        $this->assertThat(
            new ClassWithNonPublicAttributes,
            $this->classHasAttribute('publicAttribute')
        );
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::classHasStaticAttribute
     */
    public function testAssertThatClassHasStaticAttribute()
    {
        $this->assertThat(
            new ClassWithNonPublicAttributes,
            $this->classHasStaticAttribute('publicStaticAttribute')
        );
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::objectHasAttribute
     */
    public function testAssertThatObjectHasAttribute()
    {
        $this->assertThat(
            new ClassWithNonPublicAttributes,
            $this->objectHasAttribute('publicAttribute')
        );
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::equalTo
     */
    public function testAssertThatEqualTo()
    {
        $this->assertThat('foo', $this->equalTo('foo'));
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::identicalTo
     */
    public function testAssertThatIdenticalTo()
    {
        $value      = new StdClass;
        $constraint = $this->identicalTo($value);

        $this->assertThat($value, $constraint);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::isInstanceOf
     */
    public function testAssertThatIsInstanceOf()
    {
        $this->assertThat(new StdClass, $this->isInstanceOf('StdClass'));
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::isType
     */
    public function testAssertThatIsType()
    {
        $this->assertThat('string', $this->isType('string'));
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::isEmpty
     */
    public function testAssertThatIsEmpty()
    {
        $this->assertThat([], $this->isEmpty());
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::fileExists
     */
    public function testAssertThatFileExists()
    {
        $this->assertThat(__FILE__, $this->fileExists());
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::greaterThan
     */
    public function testAssertThatGreaterThan()
    {
        $this->assertThat(2, $this->greaterThan(1));
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::greaterThanOrEqual
     */
    public function testAssertThatGreaterThanOrEqual()
    {
        $this->assertThat(2, $this->greaterThanOrEqual(1));
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::lessThan
     */
    public function testAssertThatLessThan()
    {
        $this->assertThat(1, $this->lessThan(2));
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::lessThanOrEqual
     */
    public function testAssertThatLessThanOrEqual()
    {
        $this->assertThat(1, $this->lessThanOrEqual(2));
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::matchesRegularExpression
     */
    public function testAssertThatMatchesRegularExpression()
    {
        $this->assertThat('foobar', $this->matchesRegularExpression('/foo/'));
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::callback
     */
    public function testAssertThatCallback()
    {
        $this->assertThat(null, $this->callback(function ($other) { return true;
        }));
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertThat
     * @covers \PhpUnit\Framework\Assert::countOf
     */
    public function testAssertThatCountOf()
    {
        $this->assertThat([1], $this->countOf(1));
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertFileEquals
     */
    public function testAssertFileEquals()
    {
        $this->assertFileEquals(
            $this->filesDirectory . 'foo.xml',
            $this->filesDirectory . 'foo.xml'
        );

        try {
            $this->assertFileEquals(
                $this->filesDirectory . 'foo.xml',
                $this->filesDirectory . 'bar.xml'
            );
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertFileNotEquals
     */
    public function testAssertFileNotEquals()
    {
        $this->assertFileNotEquals(
            $this->filesDirectory . 'foo.xml',
            $this->filesDirectory . 'bar.xml'
        );

        try {
            $this->assertFileNotEquals(
                $this->filesDirectory . 'foo.xml',
                $this->filesDirectory . 'foo.xml'
            );
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertStringEqualsFile
     */
    public function testAssertStringEqualsFile()
    {
        $this->assertStringEqualsFile(
            $this->filesDirectory . 'foo.xml',
            file_get_contents($this->filesDirectory . 'foo.xml')
        );

        try {
            $this->assertStringEqualsFile(
                $this->filesDirectory . 'foo.xml',
                file_get_contents($this->filesDirectory . 'bar.xml')
            );
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertStringNotEqualsFile
     */
    public function testAssertStringNotEqualsFile()
    {
        $this->assertStringNotEqualsFile(
            $this->filesDirectory . 'foo.xml',
            file_get_contents($this->filesDirectory . 'bar.xml')
        );

        try {
            $this->assertStringNotEqualsFile(
                $this->filesDirectory . 'foo.xml',
                file_get_contents($this->filesDirectory . 'foo.xml')
            );
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertStringStartsWith
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertStringStartsWithThrowsException()
    {
        $this->assertStringStartsWith(null, null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertStringStartsWith
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertStringStartsWithThrowsException2()
    {
        $this->assertStringStartsWith('', null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertStringStartsNotWith
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertStringStartsNotWithThrowsException()
    {
        $this->assertStringStartsNotWith(null, null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertStringStartsNotWith
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertStringStartsNotWithThrowsException2()
    {
        $this->assertStringStartsNotWith('', null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertStringEndsWith
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertStringEndsWithThrowsException()
    {
        $this->assertStringEndsWith(null, null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertStringEndsWith
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertStringEndsWithThrowsException2()
    {
        $this->assertStringEndsWith('', null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertStringEndsNotWith
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertStringEndsNotWithThrowsException()
    {
        $this->assertStringEndsNotWith(null, null);
    }

    /**
     * @covers            \PhpUnit\Framework\Assert::assertStringEndsNotWith
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertStringEndsNotWithThrowsException2()
    {
        $this->assertStringEndsNotWith('', null);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertStringStartsWith
     */
    public function testAssertStringStartsWith()
    {
        $this->assertStringStartsWith('prefix', 'prefixfoo');

        try {
            $this->assertStringStartsWith('prefix', 'foo');
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertStringStartsNotWith
     */
    public function testAssertStringStartsNotWith()
    {
        $this->assertStringStartsNotWith('prefix', 'foo');

        try {
            $this->assertStringStartsNotWith('prefix', 'prefixfoo');
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertStringEndsWith
     */
    public function testAssertStringEndsWith()
    {
        $this->assertStringEndsWith('suffix', 'foosuffix');

        try {
            $this->assertStringEndsWith('suffix', 'foo');
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertStringEndsNotWith
     */
    public function testAssertStringEndsNotWith()
    {
        $this->assertStringEndsNotWith('suffix', 'foo');

        try {
            $this->assertStringEndsNotWith('suffix', 'foosuffix');
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertStringMatchesFormat
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertStringMatchesFormatRaisesExceptionForInvalidFirstArgument()
    {
        $this->assertStringMatchesFormat(null, '');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertStringMatchesFormat
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertStringMatchesFormatRaisesExceptionForInvalidSecondArgument()
    {
        $this->assertStringMatchesFormat('', null);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertStringMatchesFormat
     */
    public function testAssertStringMatchesFormat()
    {
        $this->assertStringMatchesFormat('*%s*', '***');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertStringMatchesFormat
     * @expectedException \PhpUnit\Framework\AssertionFailedError
     */
    public function testAssertStringMatchesFormatFailure()
    {
        $this->assertStringMatchesFormat('*%s*', '**');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertStringNotMatchesFormat
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertStringNotMatchesFormatRaisesExceptionForInvalidFirstArgument()
    {
        $this->assertStringNotMatchesFormat(null, '');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertStringNotMatchesFormat
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertStringNotMatchesFormatRaisesExceptionForInvalidSecondArgument()
    {
        $this->assertStringNotMatchesFormat('', null);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertStringNotMatchesFormat
     */
    public function testAssertStringNotMatchesFormat()
    {
        $this->assertStringNotMatchesFormat('*%s*', '**');

        try {
            $this->assertStringMatchesFormat('*%s*', '**');
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertEmpty
     */
    public function testAssertEmpty()
    {
        $this->assertEmpty([]);

        try {
            $this->assertEmpty(['foo']);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotEmpty
     */
    public function testAssertNotEmpty()
    {
        $this->assertNotEmpty(['foo']);

        try {
            $this->assertNotEmpty([]);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeEmpty
     */
    public function testAssertAttributeEmpty()
    {
        $o    = new StdClass;
        $o->a = [];

        $this->assertAttributeEmpty('a', $o);

        try {
            $o->a = ['b'];
            $this->assertAttributeEmpty('a', $o);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeNotEmpty
     */
    public function testAssertAttributeNotEmpty()
    {
        $o    = new StdClass;
        $o->a = ['b'];

        $this->assertAttributeNotEmpty('a', $o);

        try {
            $o->a = [];
            $this->assertAttributeNotEmpty('a', $o);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::markTestIncomplete
     */
    public function testMarkTestIncomplete()
    {
        try {
            $this->markTestIncomplete('incomplete');
        } catch (IncompleteTestError $e) {
            $this->assertEquals('incomplete', $e->getMessage());

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::markTestSkipped
     */
    public function testMarkTestSkipped()
    {
        try {
            $this->markTestSkipped('skipped');
        } catch (SkippedTestError $e) {
            $this->assertEquals('skipped', $e->getMessage());

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertCount
     */
    public function testAssertCount()
    {
        $this->assertCount(2, [1, 2]);

        try {
            $this->assertCount(2, [1, 2, 3]);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertCount
     */
    public function testAssertCountTraversable()
    {
        $this->assertCount(2, new ArrayIterator([1, 2]));

        try {
            $this->assertCount(2, new ArrayIterator([1, 2, 3]));
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertCount
     */
    public function testAssertCountThrowsExceptionIfExpectedCountIsNoInteger()
    {
        try {
            $this->assertCount('a', []);
        } catch (Exception $e) {
            $this->assertEquals('Argument #1 (No Value) of PhpUnit\\Framework\\Assert::assertCount() must be a integer', $e->getMessage());

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertCount
     */
    public function testAssertCountThrowsExceptionIfElementIsNotCountable()
    {
        try {
            $this->assertCount(2, '');
        } catch (Exception $e) {
            $this->assertEquals('Argument #2 (No Value) of PhpUnit\\Framework\\Assert::assertCount() must be a countable or traversable', $e->getMessage());

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeCount
     */
    public function testAssertAttributeCount()
    {
        $o    = new stdClass;
        $o->a = [];

        $this->assertAttributeCount(0, 'a', $o);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotCount
     */
    public function testAssertNotCount()
    {
        $this->assertNotCount(2, [1, 2, 3]);

        try {
            $this->assertNotCount(2, [1, 2]);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotCount
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertNotCountThrowsExceptionIfExpectedCountIsNoInteger()
    {
        $this->assertNotCount('a', []);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotCount
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertNotCountThrowsExceptionIfElementIsNotCountable()
    {
        $this->assertNotCount(2, '');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeNotCount
     */
    public function testAssertAttributeNotCount()
    {
        $o    = new stdClass;
        $o->a = [];

        $this->assertAttributeNotCount(1, 'a', $o);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertSameSize
     */
    public function testAssertSameSize()
    {
        $this->assertSameSize([1, 2], [3, 4]);

        try {
            $this->assertSameSize([1, 2], [1, 2, 3]);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertSameSize
     */
    public function testAssertSameSizeThrowsExceptionIfExpectedIsNotCountable()
    {
        try {
            $this->assertSameSize('a', []);
        } catch (Exception $e) {
            $this->assertEquals('Argument #1 (No Value) of PhpUnit\\Framework\\Assert::assertSameSize() must be a countable or traversable', $e->getMessage());

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertSameSize
     */
    public function testAssertSameSizeThrowsExceptionIfActualIsNotCountable()
    {
        try {
            $this->assertSameSize([], '');
        } catch (Exception $e) {
            $this->assertEquals('Argument #2 (No Value) of PhpUnit\\Framework\\Assert::assertSameSize() must be a countable or traversable', $e->getMessage());

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotSameSize
     */
    public function testAssertNotSameSize()
    {
        $this->assertNotSameSize([1, 2], [1, 2, 3]);

        try {
            $this->assertNotSameSize([1, 2], [3, 4]);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotSameSize
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertNotSameSizeThrowsExceptionIfExpectedIsNotCountable()
    {
        $this->assertNotSameSize('a', []);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotSameSize
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertNotSameSizeThrowsExceptionIfActualIsNotCountable()
    {
        $this->assertNotSameSize([], '');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertJson
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertJsonRaisesExceptionForInvalidArgument()
    {
        $this->assertJson(null);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertJson
     */
    public function testAssertJson()
    {
        $this->assertJson('{}');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertJsonStringEqualsJsonString
     */
    public function testAssertJsonStringEqualsJsonString()
    {
        $expected = '{"Mascott" : "Tux"}';
        $actual   = '{"Mascott" : "Tux"}';
        $message  = 'Given Json strings do not match';

        $this->assertJsonStringEqualsJsonString($expected, $actual, $message);
    }

    /**
     * @dataProvider validInvalidJsonDataprovider
     * @covers \PhpUnit\Framework\Assert::assertJsonStringEqualsJsonString
     */
    public function testAssertJsonStringEqualsJsonStringErrorRaised($expected, $actual)
    {
        try {
            $this->assertJsonStringEqualsJsonString($expected, $actual);
        } catch (AssertionFailedError $e) {
            return;
        }
        $this->fail('Expected exception not found');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertJsonStringNotEqualsJsonString
     */
    public function testAssertJsonStringNotEqualsJsonString()
    {
        $expected = '{"Mascott" : "Beastie"}';
        $actual   = '{"Mascott" : "Tux"}';
        $message  = 'Given Json strings do match';

        $this->assertJsonStringNotEqualsJsonString($expected, $actual, $message);
    }

    /**
     * @dataProvider validInvalidJsonDataprovider
     * @covers \PhpUnit\Framework\Assert::assertJsonStringNotEqualsJsonString
     */
    public function testAssertJsonStringNotEqualsJsonStringErrorRaised($expected, $actual)
    {
        try {
            $this->assertJsonStringNotEqualsJsonString($expected, $actual);
        } catch (AssertionFailedError $e) {
            return;
        }
        $this->fail('Expected exception not found');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertJsonStringEqualsJsonFile
     */
    public function testAssertJsonStringEqualsJsonFile()
    {
        $file    = __DIR__ . '/../_files/JsonData/simpleObject.json';
        $actual  = json_encode(['Mascott' => 'Tux']);
        $message = '';
        $this->assertJsonStringEqualsJsonFile($file, $actual, $message);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertJsonStringEqualsJsonFile
     */
    public function testAssertJsonStringEqualsJsonFileExpectingExpectationFailedException()
    {
        $file    = __DIR__ . '/../_files/JsonData/simpleObject.json';
        $actual  = json_encode(['Mascott' => 'Beastie']);
        $message = '';
        try {
            $this->assertJsonStringEqualsJsonFile($file, $actual, $message);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
                'Failed asserting that \'{"Mascott":"Beastie"}\' matches JSON string "{"Mascott":"Tux"}".',
                $e->getMessage()
            );

            return;
        }

        $this->fail('Expected Exception not thrown.');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertJsonStringEqualsJsonFile
     */
    public function testAssertJsonStringEqualsJsonFileExpectingException()
    {
        $file = __DIR__ . '/../_files/JsonData/simpleObject.json';
        try {
            $this->assertJsonStringEqualsJsonFile($file, null);
        } catch (Exception $e) {
            return;
        }
        $this->fail('Expected Exception not thrown.');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertJsonStringNotEqualsJsonFile
     */
    public function testAssertJsonStringNotEqualsJsonFile()
    {
        $file    = __DIR__ . '/../_files/JsonData/simpleObject.json';
        $actual  = json_encode(['Mascott' => 'Beastie']);
        $message = '';
        $this->assertJsonStringNotEqualsJsonFile($file, $actual, $message);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertJsonStringNotEqualsJsonFile
     */
    public function testAssertJsonStringNotEqualsJsonFileExpectingException()
    {
        $file = __DIR__ . '/../_files/JsonData/simpleObject.json';
        try {
            $this->assertJsonStringNotEqualsJsonFile($file, null);
        } catch (Exception $e) {
            return;
        }
        $this->fail('Expected exception not found.');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertJsonFileNotEqualsJsonFile
     */
    public function testAssertJsonFileNotEqualsJsonFile()
    {
        $fileExpected = __DIR__ . '/../_files/JsonData/simpleObject.json';
        $fileActual   = __DIR__ . '/../_files/JsonData/arrayObject.json';
        $message      = '';
        $this->assertJsonFileNotEqualsJsonFile($fileExpected, $fileActual, $message);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertJsonFileEqualsJsonFile
     */
    public function testAssertJsonFileEqualsJsonFile()
    {
        $file    = __DIR__ . '/../_files/JsonData/simpleObject.json';
        $message = '';
        $this->assertJsonFileEqualsJsonFile($file, $file, $message);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertInstanceOf
     */
    public function testAssertInstanceOf()
    {
        $this->assertInstanceOf('stdClass', new stdClass);

        try {
            $this->assertInstanceOf('Exception', new stdClass);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertInstanceOf
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertInstanceOfThrowsExceptionForInvalidArgument()
    {
        $this->assertInstanceOf(null, new stdClass);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeInstanceOf
     */
    public function testAssertAttributeInstanceOf()
    {
        $o    = new stdClass;
        $o->a = new stdClass;

        $this->assertAttributeInstanceOf('stdClass', 'a', $o);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotInstanceOf
     */
    public function testAssertNotInstanceOf()
    {
        $this->assertNotInstanceOf('Exception', new stdClass);

        try {
            $this->assertNotInstanceOf('stdClass', new stdClass);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotInstanceOf
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertNotInstanceOfThrowsExceptionForInvalidArgument()
    {
        $this->assertNotInstanceOf(null, new stdClass);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeNotInstanceOf
     */
    public function testAssertAttributeNotInstanceOf()
    {
        $o    = new stdClass;
        $o->a = new stdClass;

        $this->assertAttributeNotInstanceOf('Exception', 'a', $o);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertInternalType
     */
    public function testAssertInternalType()
    {
        $this->assertInternalType('integer', 1);

        try {
            $this->assertInternalType('string', 1);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertInternalType
     */
    public function testAssertInternalTypeDouble()
    {
        $this->assertInternalType('double', 1.0);

        try {
            $this->assertInternalType('double', 1);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertInternalType
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertInternalTypeThrowsExceptionForInvalidArgument()
    {
        $this->assertInternalType(null, 1);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeInternalType
     */
    public function testAssertAttributeInternalType()
    {
        $o    = new stdClass;
        $o->a = 1;

        $this->assertAttributeInternalType('integer', 'a', $o);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotInternalType
     */
    public function testAssertNotInternalType()
    {
        $this->assertNotInternalType('string', 1);

        try {
            $this->assertNotInternalType('integer', 1);
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertNotInternalType
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertNotInternalTypeThrowsExceptionForInvalidArgument()
    {
        $this->assertNotInternalType(null, 1);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertAttributeNotInternalType
     */
    public function testAssertAttributeNotInternalType()
    {
        $o    = new stdClass;
        $o->a = 1;

        $this->assertAttributeNotInternalType('string', 'a', $o);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertStringMatchesFormatFile
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertStringMatchesFormatFileThrowsExceptionForInvalidArgument()
    {
        $this->assertStringMatchesFormatFile('not_existing_file', '');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertStringMatchesFormatFile
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertStringMatchesFormatFileThrowsExceptionForInvalidArgument2()
    {
        $this->assertStringMatchesFormatFile($this->filesDirectory . 'expectedFileFormat.txt', null);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertStringMatchesFormatFile
     */
    public function testAssertStringMatchesFormatFile()
    {
        $this->assertStringMatchesFormatFile($this->filesDirectory . 'expectedFileFormat.txt', "FOO\n");

        try {
            $this->assertStringMatchesFormatFile($this->filesDirectory . 'expectedFileFormat.txt', "BAR\n");
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertStringNotMatchesFormatFile
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertStringNotMatchesFormatFileThrowsExceptionForInvalidArgument()
    {
        $this->assertStringNotMatchesFormatFile('not_existing_file', '');
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertStringNotMatchesFormatFile
     * @expectedException \PhpUnit\Framework\Exception
     */
    public function testAssertStringNotMatchesFormatFileThrowsExceptionForInvalidArgument2()
    {
        $this->assertStringNotMatchesFormatFile($this->filesDirectory . 'expectedFileFormat.txt', null);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::assertStringNotMatchesFormatFile
     */
    public function testAssertStringNotMatchesFormatFile()
    {
        $this->assertStringNotMatchesFormatFile($this->filesDirectory . 'expectedFileFormat.txt', "BAR\n");

        try {
            $this->assertStringNotMatchesFormatFile($this->filesDirectory . 'expectedFileFormat.txt', "FOO\n");
        } catch (AssertionFailedError $e) {
            return;
        }

        $this->fail();
    }

    public static function validInvalidJsonDataprovider()
    {
        return [
            'error syntax in expected JSON' => ['{"Mascott"::}', '{"Mascott" : "Tux"}'],
            'error UTF-8 in actual JSON'    => ['{"Mascott" : "Tux"}', '{"Mascott" : :}'],
        ];
    }
}
