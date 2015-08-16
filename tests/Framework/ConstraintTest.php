<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PhpUnit\Framework\Assert;
use PhpUnit\Framework\Constraint;
use PhpUnit\Framework\ExpectationFailedException;
use PhpUnit\Framework\TestCase;
use PhpUnit\Framework\TestFailure;

/**
 * @since      Class available since Release 3.0.0
 */
class Framework_ConstraintTest extends TestCase
{
    /**
     * @covers PHPUnit_Framework_Constraint_ArrayHasKey
     * @covers \PhpUnit\Framework\Assert::arrayHasKey
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintArrayHasKey()
    {
        $constraint = Assert::arrayHasKey(0);

        $this->assertFalse($constraint->evaluate([], '', true));
        $this->assertEquals('has the key 0', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate([]);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(<<<EOF
Failed asserting that an array has the key 0.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers PHPUnit_Framework_Constraint_ArrayHasKey
     * @covers \PhpUnit\Framework\Assert::arrayHasKey
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintArrayHasKey2()
    {
        $constraint = Assert::arrayHasKey(0);

        try {
            $constraint->evaluate([], 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
custom message\nFailed asserting that an array has the key 0.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers PHPUnit_Framework_Constraint_ArrayHasKey
     * @covers \PhpUnit\Framework\Assert::arrayHasKey
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintArrayNotHasKey()
    {
        $constraint = Assert::logicalNot(
            Assert::arrayHasKey(0)
        );

        $this->assertFalse($constraint->evaluate([0 => 1], '', true));
        $this->assertEquals('does not have the key 0', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate([0 => 1]);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that an array does not have the key 0.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers PHPUnit_Framework_Constraint_ArrayHasKey
     * @covers \PhpUnit\Framework\Assert::arrayHasKey
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintArrayNotHasKey2()
    {
        $constraint = Assert::logicalNot(
          Assert::arrayHasKey(0)
        );

        try {
            $constraint->evaluate([0], 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
custom message
Failed asserting that an array does not have the key 0.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::fileExists
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\Constraint\FileExists
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintFileExists()
    {
        $constraint = Assert::fileExists();

        $this->assertFalse($constraint->evaluate('foo', '', true));
        $this->assertEquals('file exists', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate('foo');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that file "foo" exists.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::fileExists
     * @covers \PhpUnit\Framework\Constraint\FileExists
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintFileExists2()
    {
        $constraint = Assert::fileExists();

        try {
            $constraint->evaluate('foo', 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(<<<EOF
custom message
Failed asserting that file "foo" exists.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::fileExists
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\FileExists
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintFileNotExists()
    {
        $file = dirname(__DIR__) . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'ClassWithNonPublicAttributes.php';

        $constraint = Assert::logicalNot(
            Assert::fileExists()
        );

        $this->assertFalse($constraint->evaluate($file, '', true));
        $this->assertEquals('file does not exist', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate($file);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that file "$file" does not exist.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::fileExists
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\FileExists
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintFileNotExists2()
    {
        $file = dirname(__DIR__) . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . 'ClassWithNonPublicAttributes.php';

        $constraint = Assert::logicalNot(
            Assert::fileExists()
        );

        try {
            $constraint->evaluate($file, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(<<<EOF
custom message
Failed asserting that file "$file" does not exist.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::greaterThan
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\Constraint\GreaterThan
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintGreaterThan()
    {
        $constraint = Assert::greaterThan(1);

        $this->assertFalse($constraint->evaluate(0, '', true));
        $this->assertTrue($constraint->evaluate(2, '', true));
        $this->assertEquals('is greater than 1', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate(0);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that 0 is greater than 1.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::greaterThan
     * @covers \PhpUnit\Framework\Constraint\GreaterThan
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintGreaterThan2()
    {
        $constraint = Assert::greaterThan(1);

        try {
            $constraint->evaluate(0, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
custom message
Failed asserting that 0 is greater than 1.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::greaterThan
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\GreaterThan
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintNotGreaterThan()
    {
        $constraint = Assert::logicalNot(
            Assert::greaterThan(1)
        );

        $this->assertTrue($constraint->evaluate(1, '', true));
        $this->assertEquals('is not greater than 1', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate(2);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that 2 is not greater than 1.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::greaterThan
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\GreaterThan
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintNotGreaterThan2()
    {
        $constraint = Assert::logicalNot(
            Assert::greaterThan(1)
        );

        try {
            $constraint->evaluate(2, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
custom message
Failed asserting that 2 is not greater than 1.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::greaterThanOrEqual
     * @covers \PhpUnit\Framework\Constraint\GreaterThan
     * @covers \PhpUnit\Framework\Constraint\IsEqual
     * @covers \PhpUnit\Framework\Constraint\LogicalOr
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintGreaterThanOrEqual()
    {
        $constraint = Assert::greaterThanOrEqual(1);

        $this->assertTrue($constraint->evaluate(1, '', true));
        $this->assertFalse($constraint->evaluate(0, '', true));
        $this->assertEquals('is equal to 1 or is greater than 1', $constraint->toString());
        $this->assertEquals(2, count($constraint));

        try {
            $constraint->evaluate(0);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that 0 is equal to 1 or is greater than 1.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::greaterThanOrEqual
     * @covers \PhpUnit\Framework\Constraint\GreaterThan
     * @covers \PhpUnit\Framework\Constraint\IsEqual
     * @covers \PhpUnit\Framework\Constraint\LogicalOr
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintGreaterThanOrEqual2()
    {
        $constraint = Assert::greaterThanOrEqual(1);

        try {
            $constraint->evaluate(0, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
custom message
Failed asserting that 0 is equal to 1 or is greater than 1.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::greaterThanOrEqual
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\GreaterThan
     * @covers \PhpUnit\Framework\Constraint\IsEqual
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\Constraint\LogicalOr
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintNotGreaterThanOrEqual()
    {
        $constraint = Assert::logicalNot(
            Assert::greaterThanOrEqual(1)
        );

        $this->assertFalse($constraint->evaluate(1, '', true));
        $this->assertEquals('not( is equal to 1 or is greater than 1 )', $constraint->toString());
        $this->assertEquals(2, count($constraint));

        try {
            $constraint->evaluate(1);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that not( 1 is equal to 1 or is greater than 1 ).

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::greaterThanOrEqual
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\GreaterThan
     * @covers \PhpUnit\Framework\Constraint\IsEqual
     * @covers \PhpUnit\Framework\Constraint\LogicalOr
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintNotGreaterThanOrEqual2()
    {
        $constraint = Assert::logicalNot(
            Assert::greaterThanOrEqual(1)
        );

        try {
            $constraint->evaluate(1, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
custom message
Failed asserting that not( 1 is equal to 1 or is greater than 1 ).

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::anything
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\Constraint\Anything
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintAnything()
    {
        $constraint = Assert::anything();

        $this->assertTrue($constraint->evaluate(null, '', true));
        $this->assertNull($constraint->evaluate(null));
        $this->assertEquals('is anything', $constraint->toString());
        $this->assertEquals(0, count($constraint));
    }

    /**
     * @covers \PhpUnit\Framework\Assert::anything
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\Anything
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintNotAnything()
    {
        $constraint = Assert::logicalNot(
            Assert::anything()
        );

        $this->assertFalse($constraint->evaluate(null, '', true));
        $this->assertEquals('is not anything', $constraint->toString());
        $this->assertEquals(0, count($constraint));

        try {
            $constraint->evaluate(null);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that null is not anything.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::equalTo
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\Constraint\IsEqual
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintIsEqual()
    {
        $constraint = Assert::equalTo(1);

        $this->assertTrue($constraint->evaluate(1, '', true));
        $this->assertFalse($constraint->evaluate(0, '', true));
        $this->assertEquals('is equal to 1', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate(0);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that 0 matches expected 1.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    public function isEqualProvider()
    {
        $a      = new stdClass;
        $a->foo = 'bar';
        $b      = new stdClass;
        $ahash  = spl_object_hash($a);
        $bhash  = spl_object_hash($b);

        $c               = new stdClass;
        $c->foo          = 'bar';
        $c->int          = 1;
        $c->array        = [0, [1], [2], 3];
        $c->related      = new stdClass;
        $c->related->foo = "a\nb\nc\nd\ne\nf\ng\nh\ni\nj\nk";
        $c->self         = $c;
        $c->c            = $c;
        $d               = new stdClass;
        $d->foo          = 'bar';
        $d->int          = 2;
        $d->array        = [0, [4], [2], 3];
        $d->related      = new stdClass;
        $d->related->foo = "a\np\nc\nd\ne\nf\ng\nh\ni\nw\nk";
        $d->self         = $d;
        $d->c            = $c;

        $storage1 = new SplObjectStorage;
        $storage1->attach($a);
        $storage1->attach($b);
        $storage2 = new SplObjectStorage;
        $storage2->attach($b);
        $storage1hash = spl_object_hash($storage1);
        $storage2hash = spl_object_hash($storage2);

        $dom1                     = new DOMDocument;
        $dom1->preserveWhiteSpace = false;
        $dom1->loadXML('<root></root>');
        $dom2                     = new DOMDocument;
        $dom2->preserveWhiteSpace = false;
        $dom2->loadXML('<root><foo/></root>');

        $data = [
            [1, 0, <<<EOF
Failed asserting that 0 matches expected 1.

EOF
            ],
            [1.1, 0, <<<EOF
Failed asserting that 0 matches expected 1.1.

EOF
            ],
            ['a', 'b', <<<EOF
Failed asserting that two strings are equal.
--- Expected
+++ Actual
@@ @@
-'a'
+'b'

EOF
            ],
            ["a\nb\nc\nd\ne\nf\ng\nh\ni\nj\nk", "a\np\nc\nd\ne\nf\ng\nh\ni\nw\nk", <<<EOF
Failed asserting that two strings are equal.
--- Expected
+++ Actual
@@ @@
 'a
-b
+p

@@ @@
 i
-j
+w
 k'

EOF
            ],
            [1, [0], <<<EOF
Array (...) does not match expected type "integer".

EOF
            ],
            [[0], 1, <<<EOF
1 does not match expected type "array".

EOF
            ],
            [[0], [1], <<<EOF
Failed asserting that two arrays are equal.
--- Expected
+++ Actual
@@ @@
 Array (
-    0 => 0
+    0 => 1
 )

EOF
            ],
            [[true], ['true'], <<<EOF
Failed asserting that two arrays are equal.
--- Expected
+++ Actual
@@ @@
 Array (
-    0 => true
+    0 => 'true'
 )

EOF
            ],
            [[0, [1], [2], 3], [0, [4], [2], 3], <<<EOF
Failed asserting that two arrays are equal.
--- Expected
+++ Actual
@@ @@
 Array (
     0 => 0
     1 => Array (
-        0 => 1
+        0 => 4
     )
     2 => Array (...)
     3 => 3
 )

EOF
            ],
            [$a, [0], <<<EOF
Array (...) does not match expected type "object".

EOF
            ],
            [[0], $a, <<<EOF
stdClass Object (...) does not match expected type "array".

EOF
            ],
            [$a, $b, <<<EOF
Failed asserting that two objects are equal.
--- Expected
+++ Actual
@@ @@
 stdClass Object (
-    'foo' => 'bar'
 )

EOF
            ],
            [$c, $d, <<<EOF
Failed asserting that two objects are equal.
--- Expected
+++ Actual
@@ @@
 stdClass Object (
     'foo' => 'bar'
-    'int' => 1
+    'int' => 2
     'array' => Array (
         0 => 0
         1 => Array (
-            0 => 1
+            0 => 4

@@ @@
         'foo' => 'a
-        b
+        p

@@ @@
         i
-        j
+        w
         k'
     )
     'self' => stdClass Object (...)
     'c' => stdClass Object (...)
 )

EOF
            ],
            [$dom1, $dom2, <<<EOF
Failed asserting that two DOM documents are equal.
--- Expected
+++ Actual
@@ @@
 <?xml version="1.0"?>
-<root/>
+<root>
+  <foo/>
+</root>

EOF
            ],
            [
              new DateTime('2013-03-29 04:13:35', new DateTimeZone('America/New_York')),
              new DateTime('2013-03-29 04:13:35', new DateTimeZone('America/Chicago')),
              <<<EOF
Failed asserting that two DateTime objects are equal.
--- Expected
+++ Actual
@@ @@
-2013-03-29T04:13:35-0400
+2013-03-29T04:13:35-0500

EOF
            ],
        ];

        if (PHP_MAJOR_VERSION < 7) {
            $data[] = [$storage1, $storage2, <<<EOF
Failed asserting that two objects are equal.
--- Expected
+++ Actual
@@ @@
-SplObjectStorage Object &$storage1hash (
-    '$ahash' => Array &0 (
-        'obj' => stdClass Object &$ahash (
-            'foo' => 'bar'
-        )
+SplObjectStorage Object &$storage2hash (
+    '$bhash' => Array &0 (
+        'obj' => stdClass Object &$bhash ()
         'inf' => null
     )
-    '$bhash' => Array &0
 )

EOF
            ];
        } else {
            $data[] = [$storage1, $storage2, <<<EOF
Failed asserting that two objects are equal.
--- Expected
+++ Actual
@@ @@
-SplObjectStorage Object &$storage1hash (
-    '$ahash' => Array &0 (
-        'obj' => stdClass Object &$ahash (
-            'foo' => 'bar'
-        )
-        'inf' => null
-    )
-    '$bhash' => Array &1 (
+SplObjectStorage Object &$storage2hash (
+    '$bhash' => Array &0 (
         'obj' => stdClass Object &$bhash ()
         'inf' => null
     )
 )

EOF
            ];
        }

        return $data;
    }

    /**
     * @dataProvider isEqualProvider
     * @covers \PhpUnit\Framework\Assert::equalTo
     * @covers \PhpUnit\Framework\Constraint\IsEqual
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintIsEqual2($expected, $actual, $message)
    {
        $constraint = Assert::equalTo($expected);

        try {
            $constraint->evaluate($actual, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              "custom message\n$message",
              $this->trimnl(TestFailure::exceptionToString($e))
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::equalTo
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\IsEqual
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintIsNotEqual()
    {
        $constraint = Assert::logicalNot(
            Assert::equalTo(1)
        );

        $this->assertTrue($constraint->evaluate(0, '', true));
        $this->assertFalse($constraint->evaluate(1, '', true));
        $this->assertEquals('is not equal to 1', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate(1);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that 1 is not equal to 1.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::equalTo
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\IsEqual
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintIsNotEqual2()
    {
        $constraint = Assert::logicalNot(
            Assert::equalTo(1)
        );

        try {
            $constraint->evaluate(1, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
custom message
Failed asserting that 1 is not equal to 1.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::identicalTo
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\Constraint\IsIdentical
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintIsIdentical()
    {
        $a = new stdClass;
        $b = new stdClass;

        $constraint = Assert::identicalTo($a);

        $this->assertFalse($constraint->evaluate($b, '', true));
        $this->assertTrue($constraint->evaluate($a, '', true));
        $this->assertEquals('is identical to an object of class "stdClass"', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate($b);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(<<<EOF
Failed asserting that two variables reference the same object.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::identicalTo
     * @covers \PhpUnit\Framework\Constraint\IsIdentical
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintIsIdentical2()
    {
        $a = new stdClass;
        $b = new stdClass;

        $constraint = Assert::identicalTo($a);

        try {
            $constraint->evaluate($b, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(<<<EOF
custom message
Failed asserting that two variables reference the same object.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::identicalTo
     * @covers \PhpUnit\Framework\Constraint\IsIdentical
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintIsIdentical3()
    {
        $constraint = Assert::identicalTo('a');

        try {
            $constraint->evaluate('b', 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(<<<EOF
custom message
Failed asserting that two strings are identical.
--- Expected
+++ Actual
@@ @@
-a
+b

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::identicalTo
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\IsIdentical
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintIsNotIdentical()
    {
        $a = new stdClass;
        $b = new stdClass;

        $constraint = Assert::logicalNot(
            Assert::identicalTo($a)
        );

        $this->assertTrue($constraint->evaluate($b, '', true));
        $this->assertFalse($constraint->evaluate($a, '', true));
        $this->assertEquals('is not identical to an object of class "stdClass"', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate($a);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(<<<EOF
Failed asserting that two variables don't reference the same object.

EOF
              ,
              $this->trimnl(TestFailure::exceptionToString($e))
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::identicalTo
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\IsIdentical
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintIsNotIdentical2()
    {
        $a = new stdClass;

        $constraint = Assert::logicalNot(
            Assert::identicalTo($a)
        );

        try {
            $constraint->evaluate($a, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(<<<EOF
custom message
Failed asserting that two variables don't reference the same object.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::identicalTo
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\IsIdentical
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintIsNotIdentical3()
    {
        $constraint = Assert::logicalNot(
            Assert::identicalTo('a')
        );

        try {
            $constraint->evaluate('a', 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(<<<EOF
custom message
Failed asserting that two strings are not identical.

EOF
              ,
              $this->trimnl(TestFailure::exceptionToString($e))
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::isInstanceOf
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\Constraint\IsInstanceOf
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintIsInstanceOf()
    {
        $constraint = Assert::isInstanceOf('Exception');

        $this->assertFalse($constraint->evaluate(new stdClass, '', true));
        $this->assertTrue($constraint->evaluate(new Exception, '', true));
        $this->assertEquals('is instance of class "Exception"', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        $interfaceConstraint = Assert::isInstanceOf('Countable');
        $this->assertFalse($interfaceConstraint->evaluate(new stdClass, '', true));
        $this->assertTrue($interfaceConstraint->evaluate(new ArrayObject, '', true));
        $this->assertEquals('is instance of interface "Countable"', $interfaceConstraint->toString());

        try {
            $constraint->evaluate(new stdClass);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that stdClass Object () is an instance of class "Exception".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::isInstanceOf
     * @covers \PhpUnit\Framework\Constraint\IsInstanceOf
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintIsInstanceOf2()
    {
        $constraint = Assert::isInstanceOf('Exception');

        try {
            $constraint->evaluate(new stdClass, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(<<<EOF
custom message
Failed asserting that stdClass Object () is an instance of class "Exception".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::isInstanceOf
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\IsInstanceOf
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintIsNotInstanceOf()
    {
        $constraint = Assert::logicalNot(
            Assert::isInstanceOf('stdClass')
        );

        $this->assertFalse($constraint->evaluate(new stdClass, '', true));
        $this->assertTrue($constraint->evaluate(new Exception, '', true));
        $this->assertEquals('is not instance of class "stdClass"', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate(new stdClass);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that stdClass Object () is not an instance of class "stdClass".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::isInstanceOf
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\IsInstanceOf
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintIsNotInstanceOf2()
    {
        $constraint = Assert::logicalNot(
            Assert::isInstanceOf('stdClass')
        );

        try {
            $constraint->evaluate(new stdClass, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(<<<EOF
custom message
Failed asserting that stdClass Object () is not an instance of class "stdClass".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::isType
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\Constraint\IsType
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintIsType()
    {
        $constraint = Assert::isType('string');

        $this->assertFalse($constraint->evaluate(0, '', true));
        $this->assertTrue($constraint->evaluate('', '', true));
        $this->assertEquals('is of type "string"', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate(new stdClass);
        } catch (ExpectationFailedException $e) {
            $this->assertStringMatchesFormat(<<<EOF
Failed asserting that stdClass Object &%x () is of type "string".

EOF
              ,
              $this->trimnl(TestFailure::exceptionToString($e))
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::isType
     * @covers \PhpUnit\Framework\Constraint\IsType
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintIsType2()
    {
        $constraint = Assert::isType('string');

        try {
            $constraint->evaluate(new stdClass, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertStringMatchesFormat(<<<EOF
custom message
Failed asserting that stdClass Object &%x () is of type "string".

EOF
              ,
              $this->trimnl(TestFailure::exceptionToString($e))
            );

            return;
        }

        $this->fail();
    }

    public function resources()
    {
        $fh = fopen(__FILE__, 'r');
        fclose($fh);

        return [
            'open resource'     => [fopen(__FILE__, 'r')],
            'closed resource'   => [$fh],
        ];
    }

    /**
     * @dataProvider resources
     * @covers \PhpUnit\Framework\Assert::isType
     * @covers \PhpUnit\Framework\Constraint\IsType
     */
    public function testConstraintIsResourceTypeEvaluatesCorrectlyWithResources($resource)
    {
        $constraint = Assert::isType('resource');

        $this->assertTrue($constraint->evaluate($resource, '', true));

        @fclose($resource);
    }

    /**
     * @covers \PhpUnit\Framework\Assert::isType
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\IsType
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintIsNotType()
    {
        $constraint = Assert::logicalNot(
            Assert::isType('string')
        );

        $this->assertTrue($constraint->evaluate(0, '', true));
        $this->assertFalse($constraint->evaluate('', '', true));
        $this->assertEquals('is not of type "string"', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate('');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that '' is not of type "string".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::isType
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\IsType
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintIsNotType2()
    {
        $constraint = Assert::logicalNot(
            Assert::isType('string')
        );

        try {
            $constraint->evaluate('', 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(<<<EOF
custom message
Failed asserting that '' is not of type "string".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::isNull
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\Constraint\IsNull
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintIsNull()
    {
        $constraint = Assert::isNull();

        $this->assertFalse($constraint->evaluate(0, '', true));
        $this->assertTrue($constraint->evaluate(null, '', true));
        $this->assertEquals('is null', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate(0);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(<<<EOF
Failed asserting that 0 is null.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::isNull
     * @covers \PhpUnit\Framework\Constraint\IsNull
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintIsNull2()
    {
        $constraint = Assert::isNull();

        try {
            $constraint->evaluate(0, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(<<<EOF
custom message
Failed asserting that 0 is null.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::isNull
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\Constraint\IsNull
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintIsNotNull()
    {
        $constraint = Assert::logicalNot(
            Assert::isNull()
        );

        $this->assertFalse($constraint->evaluate(null, '', true));
        $this->assertTrue($constraint->evaluate(0, '', true));
        $this->assertEquals('is not null', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate(null);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(<<<EOF
Failed asserting that null is not null.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::isNull
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\IsNull
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintIsNotNull2()
    {
        $constraint = Assert::logicalNot(
            Assert::isNull()
        );

        try {
            $constraint->evaluate(null, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(<<<EOF
custom message
Failed asserting that null is not null.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::lessThan
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\Constraint\LessThan
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintLessThan()
    {
        $constraint = Assert::lessThan(1);

        $this->assertTrue($constraint->evaluate(0, '', true));
        $this->assertFalse($constraint->evaluate(1, '', true));
        $this->assertEquals('is less than 1', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate(1);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that 1 is less than 1.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::lessThan
     * @covers \PhpUnit\Framework\Constraint\LessThan
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintLessThan2()
    {
        $constraint = Assert::lessThan(1);

        try {
            $constraint->evaluate(1, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
custom message
Failed asserting that 1 is less than 1.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::lessThan
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\LessThan
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintNotLessThan()
    {
        $constraint = Assert::logicalNot(
            Assert::lessThan(1)
        );

        $this->assertTrue($constraint->evaluate(1, '', true));
        $this->assertFalse($constraint->evaluate(0, '', true));
        $this->assertEquals('is not less than 1', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate(0);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that 0 is not less than 1.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::lessThan
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\LessThan
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintNotLessThan2()
    {
        $constraint = Assert::logicalNot(
            Assert::lessThan(1)
        );

        try {
            $constraint->evaluate(0, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
custom message
Failed asserting that 0 is not less than 1.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::lessThanOrEqual
     * @covers \PhpUnit\Framework\Constraint\IsEqual
     * @covers \PhpUnit\Framework\Constraint\LessThan
     * @covers \PhpUnit\Framework\Constraint\LogicalOr
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintLessThanOrEqual()
    {
        $constraint = Assert::lessThanOrEqual(1);

        $this->assertTrue($constraint->evaluate(1, '', true));
        $this->assertFalse($constraint->evaluate(2, '', true));
        $this->assertEquals('is equal to 1 or is less than 1', $constraint->toString());
        $this->assertEquals(2, count($constraint));

        try {
            $constraint->evaluate(2);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that 2 is equal to 1 or is less than 1.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers PHPUnit_Framework_Constraint_Callback
     */
    public function testConstraintCallback()
    {
        $closureReflect = function ($parameter) {
            return $parameter;
        };

        $closureWithoutParameter = function () {
            return true;
        };

        $constraint = Assert::callback($closureWithoutParameter);
        $this->assertTrue($constraint->evaluate('', '', true));

        $constraint = Assert::callback($closureReflect);
        $this->assertTrue($constraint->evaluate(true, '', true));
        $this->assertFalse($constraint->evaluate(false, '', true));

        $callback   = [$this, 'callbackReturningTrue'];
        $constraint = Assert::callback($callback);
        $this->assertTrue($constraint->evaluate(false,  '', true));

        $callback   = ['Framework_ConstraintTest', 'staticCallbackReturningTrue'];
        $constraint = Assert::callback($callback);
        $this->assertTrue($constraint->evaluate(null, '', true));

        $this->assertEquals('is accepted by specified callback', $constraint->toString());
    }

    /**
     * @covers PHPUnit_Framework_Constraint_Callback
     * @expectedException \PhpUnit\Framework\ExpectationFailedException
     * @expectedExceptionMessage Failed asserting that 'This fails' is accepted by specified callback.
     */
    public function testConstraintCallbackFailure()
    {
        $constraint = Assert::callback(function () {
            return false;
        });
        $constraint->evaluate('This fails');
    }

    public function callbackReturningTrue()
    {
        return true;
    }

    public static function staticCallbackReturningTrue()
    {
        return true;
    }

    /**
     * @covers \PhpUnit\Framework\Assert::lessThanOrEqual
     * @covers \PhpUnit\Framework\Constraint\IsEqual
     * @covers \PhpUnit\Framework\Constraint\LessThan
     * @covers \PhpUnit\Framework\Constraint\LogicalOr
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintLessThanOrEqual2()
    {
        $constraint = Assert::lessThanOrEqual(1);

        try {
            $constraint->evaluate(2, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
custom message
Failed asserting that 2 is equal to 1 or is less than 1.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::lessThanOrEqual
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\IsEqual
     * @covers \PhpUnit\Framework\Constraint\LessThan
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\Constraint\LogicalOr
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintNotLessThanOrEqual()
    {
        $constraint = Assert::logicalNot(
            Assert::lessThanOrEqual(1)
        );

        $this->assertTrue($constraint->evaluate(2, '', true));
        $this->assertFalse($constraint->evaluate(1, '', true));
        $this->assertEquals('not( is equal to 1 or is less than 1 )', $constraint->toString());
        $this->assertEquals(2, count($constraint));

        try {
            $constraint->evaluate(1);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that not( 1 is equal to 1 or is less than 1 ).

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::lessThanOrEqual
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\IsEqual
     * @covers \PhpUnit\Framework\Constraint\LessThan
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\Constraint\LogicalOr
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintNotLessThanOrEqual2()
    {
        $constraint = Assert::logicalNot(
            Assert::lessThanOrEqual(1)
        );

        try {
            $constraint->evaluate(1, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
custom message
Failed asserting that not( 1 is equal to 1 or is less than 1 ).

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers PHPUnit_Framework_Constraint_ClassHasAttribute
     * @covers \PhpUnit\Framework\Assert::classHasAttribute
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintClassHasAttribute()
    {
        $constraint = Assert::classHasAttribute('privateAttribute');

        $this->assertTrue($constraint->evaluate('ClassWithNonPublicAttributes', '', true));
        $this->assertFalse($constraint->evaluate('stdClass', '', true));
        $this->assertEquals('has attribute "privateAttribute"', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate('stdClass');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that class "stdClass" has attribute "privateAttribute".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers PHPUnit_Framework_Constraint_ClassHasAttribute
     * @covers \PhpUnit\Framework\Assert::classHasAttribute
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintClassHasAttribute2()
    {
        $constraint = Assert::classHasAttribute('privateAttribute');

        try {
            $constraint->evaluate('stdClass', 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(<<<EOF
custom message
Failed asserting that class "stdClass" has attribute "privateAttribute".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers PHPUnit_Framework_Constraint_ClassHasAttribute
     * @covers \PhpUnit\Framework\Assert::classHasAttribute
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintClassNotHasAttribute()
    {
        $constraint = Assert::logicalNot(
            Assert::classHasAttribute('privateAttribute')
        );

        $this->assertTrue($constraint->evaluate('stdClass', '', true));
        $this->assertFalse($constraint->evaluate('ClassWithNonPublicAttributes', '', true));
        $this->assertEquals('does not have attribute "privateAttribute"', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate('ClassWithNonPublicAttributes');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that class "ClassWithNonPublicAttributes" does not have attribute "privateAttribute".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers PHPUnit_Framework_Constraint_ClassHasAttribute
     * @covers \PhpUnit\Framework\Assert::classHasAttribute
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintClassNotHasAttribute2()
    {
        $constraint = Assert::logicalNot(
            Assert::classHasAttribute('privateAttribute')
        );

        try {
            $constraint->evaluate('ClassWithNonPublicAttributes', 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(<<<EOF
custom message
Failed asserting that class "ClassWithNonPublicAttributes" does not have attribute "privateAttribute".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers PHPUnit_Framework_Constraint_ClassHasStaticAttribute
     * @covers \PhpUnit\Framework\Assert::classHasStaticAttribute
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintClassHasStaticAttribute()
    {
        $constraint = Assert::classHasStaticAttribute('privateStaticAttribute');

        $this->assertTrue($constraint->evaluate('ClassWithNonPublicAttributes', '', true));
        $this->assertFalse($constraint->evaluate('stdClass', '', true));
        $this->assertEquals('has static attribute "privateStaticAttribute"', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate('stdClass');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that class "stdClass" has static attribute "privateStaticAttribute".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers PHPUnit_Framework_Constraint_ClassHasStaticAttribute
     * @covers \PhpUnit\Framework\Assert::classHasStaticAttribute
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintClassHasStaticAttribute2()
    {
        $constraint = Assert::classHasStaticAttribute('foo');

        try {
            $constraint->evaluate('stdClass', 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(<<<EOF
custom message
Failed asserting that class "stdClass" has static attribute "foo".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers PHPUnit_Framework_Constraint_ClassHasStaticAttribute
     * @covers \PhpUnit\Framework\Assert::classHasStaticAttribute
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintClassNotHasStaticAttribute()
    {
        $constraint = Assert::logicalNot(
            Assert::classHasStaticAttribute('privateStaticAttribute')
        );

        $this->assertTrue($constraint->evaluate('stdClass', '', true));
        $this->assertFalse($constraint->evaluate('ClassWithNonPublicAttributes', '', true));
        $this->assertEquals('does not have static attribute "privateStaticAttribute"', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate('ClassWithNonPublicAttributes');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that class "ClassWithNonPublicAttributes" does not have static attribute "privateStaticAttribute".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers PHPUnit_Framework_Constraint_ClassHasStaticAttribute
     * @covers \PhpUnit\Framework\Assert::classHasStaticAttribute
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintClassNotHasStaticAttribute2()
    {
        $constraint = Assert::logicalNot(
            Assert::classHasStaticAttribute('privateStaticAttribute')
        );

        try {
            $constraint->evaluate('ClassWithNonPublicAttributes', 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(<<<EOF
custom message
Failed asserting that class "ClassWithNonPublicAttributes" does not have static attribute "privateStaticAttribute".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::objectHasAttribute
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\Constraint\ObjectHasAttribute
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintObjectHasAttribute()
    {
        $constraint = Assert::objectHasAttribute('privateAttribute');

        $this->assertTrue($constraint->evaluate(new ClassWithNonPublicAttributes, '', true));
        $this->assertFalse($constraint->evaluate(new stdClass, '', true));
        $this->assertEquals('has attribute "privateAttribute"', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate(new stdClass);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that object of class "stdClass" has attribute "privateAttribute".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::objectHasAttribute
     * @covers \PhpUnit\Framework\Constraint\ObjectHasAttribute
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintObjectHasAttribute2()
    {
        $constraint = Assert::objectHasAttribute('privateAttribute');

        try {
            $constraint->evaluate(new stdClass, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(<<<EOF
custom message
Failed asserting that object of class "stdClass" has attribute "privateAttribute".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Assert::objectHasAttribute
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\Constraint\ObjectHasAttribute
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintObjectNotHasAttribute()
    {
        $constraint = Assert::logicalNot(
            Assert::objectHasAttribute('privateAttribute')
        );

        $this->assertTrue($constraint->evaluate(new stdClass, '', true));
        $this->assertFalse($constraint->evaluate(new ClassWithNonPublicAttributes, '', true));
        $this->assertEquals('does not have attribute "privateAttribute"', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate(new ClassWithNonPublicAttributes);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that object of class "ClassWithNonPublicAttributes" does not have attribute "privateAttribute".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Assert::objectHasAttribute
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\Constraint\ObjectHasAttribute
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintObjectNotHasAttribute2()
    {
        $constraint = Assert::logicalNot(
            Assert::objectHasAttribute('privateAttribute')
        );

        try {
            $constraint->evaluate(new ClassWithNonPublicAttributes, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(<<<EOF
custom message
Failed asserting that object of class "ClassWithNonPublicAttributes" does not have attribute "privateAttribute".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::matchesRegularExpression
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\Constraint\MatchesRegularExpression
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintMatchesRegularExpression()
    {
        $constraint = Assert::matchesRegularExpression('/foo/');

        $this->assertFalse($constraint->evaluate('barbazbar', '', true));
        $this->assertTrue($constraint->evaluate('barfoobar', '', true));
        $this->assertEquals('matches regular expression pattern "/foo/"', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate('barbazbar');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that 'barbazbar' matches regular expression pattern "/foo/".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::matchesRegularExpression
     * @covers \PhpUnit\Framework\Constraint\MatchesRegularExpression
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintMatchesRegularExpression2()
    {
        $constraint = Assert::matchesRegularExpression('/foo/');

        try {
            $constraint->evaluate('barbazbar', 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(<<<EOF
custom message
Failed asserting that 'barbazbar' matches regular expression pattern "/foo/".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::matchesRegularExpression
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\Constraint\MatchesRegularExpression
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintMatchesRegularExpression3()
    {
        $constraint = Assert::logicalNot(
            Assert::matchesRegularExpression('/foo/')
        );

        $this->assertTrue($constraint->evaluate('barbazbar', '', true));
        $this->assertFalse($constraint->evaluate('barfoobar', '', true));
        $this->assertEquals('does not match regular expression pattern "/foo/"', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate('barfoobar');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that 'barfoobar' does not match regular expression pattern "/foo/".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::matchesRegularExpression
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\Constraint\MatchesRegularExpression
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintMatchesRegularExpression4()
    {
        $constraint = Assert::logicalNot(
            Assert::matchesRegularExpression('/foo/')
        );

        try {
            $constraint->evaluate('barfoobar', 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(<<<EOF
custom message
Failed asserting that 'barfoobar' does not match regular expression pattern "/foo/".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::matches
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\Constraint\StringMatches
     */
    public function testConstraintStringMatches()
    {
        $constraint = Assert::matches('*%c*');
        $this->assertFalse($constraint->evaluate('**', '', true));
        $this->assertTrue($constraint->evaluate('***', '', true));
        $this->assertEquals('matches regular expression pattern "/^\*.\*$/s"', $constraint->toString());
        $this->assertEquals(1, count($constraint));
    }

    /**
     * @covers \PhpUnit\Framework\Assert::matches
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\Constraint\StringMatches
     */
    public function testConstraintStringMatches2()
    {
        $constraint = Assert::matches('*%s*');
        $this->assertFalse($constraint->evaluate('**', '', true));
        $this->assertTrue($constraint->evaluate('***', '', true));
        $this->assertEquals('matches regular expression pattern "/^\*[^\r\n]+\*$/s"', $constraint->toString());
        $this->assertEquals(1, count($constraint));
    }

    /**
     * @covers \PhpUnit\Framework\Assert::matches
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\Constraint\StringMatches
     */
    public function testConstraintStringMatches3()
    {
        $constraint = Assert::matches('*%i*');
        $this->assertFalse($constraint->evaluate('**', '', true));
        $this->assertTrue($constraint->evaluate('*0*', '', true));
        $this->assertEquals('matches regular expression pattern "/^\*[+-]?\d+\*$/s"', $constraint->toString());
        $this->assertEquals(1, count($constraint));
    }

    /**
     * @covers \PhpUnit\Framework\Assert::matches
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\Constraint\StringMatches
     */
    public function testConstraintStringMatches4()
    {
        $constraint = Assert::matches('*%d*');
        $this->assertFalse($constraint->evaluate('**', '', true));
        $this->assertTrue($constraint->evaluate('*0*', '', true));
        $this->assertEquals('matches regular expression pattern "/^\*\d+\*$/s"', $constraint->toString());
        $this->assertEquals(1, count($constraint));
    }

    /**
     * @covers \PhpUnit\Framework\Assert::matches
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\Constraint\StringMatches
     */
    public function testConstraintStringMatches5()
    {
        $constraint = Assert::matches('*%x*');
        $this->assertFalse($constraint->evaluate('**', '', true));
        $this->assertTrue($constraint->evaluate('*0f0f0f*', '', true));
        $this->assertEquals('matches regular expression pattern "/^\*[0-9a-fA-F]+\*$/s"', $constraint->toString());
        $this->assertEquals(1, count($constraint));
    }

    /**
     * @covers \PhpUnit\Framework\Assert::matches
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\Constraint\StringMatches
     */
    public function testConstraintStringMatches6()
    {
        $constraint = Assert::matches('*%f*');
        $this->assertFalse($constraint->evaluate('**', '', true));
        $this->assertTrue($constraint->evaluate('*1.0*', '', true));
        $this->assertEquals('matches regular expression pattern "/^\*[+-]?\.?\d+\.?\d*(?:[Ee][+-]?\d+)?\*$/s"', $constraint->toString());
        $this->assertEquals(1, count($constraint));
    }

    /**
     * @covers \PhpUnit\Framework\Assert::stringStartsWith
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\Constraint\StringStartsWith
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintStringStartsWith()
    {
        $constraint = Assert::stringStartsWith('prefix');

        $this->assertFalse($constraint->evaluate('foo', '', true));
        $this->assertTrue($constraint->evaluate('prefixfoo', '', true));
        $this->assertEquals('starts with "prefix"', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate('foo');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that 'foo' starts with "prefix".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::stringStartsWith
     * @covers \PhpUnit\Framework\Constraint\StringStartsWith
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintStringStartsWith2()
    {
        $constraint = Assert::stringStartsWith('prefix');

        try {
            $constraint->evaluate('foo', 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
custom message\nFailed asserting that 'foo' starts with "prefix".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::stringStartsWith
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\Constraint\StringStartsWith
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintStringStartsNotWith()
    {
        $constraint = Assert::logicalNot(
            Assert::stringStartsWith('prefix')
        );

        $this->assertTrue($constraint->evaluate('foo', '', true));
        $this->assertFalse($constraint->evaluate('prefixfoo', '', true));
        $this->assertEquals('starts not with "prefix"', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate('prefixfoo');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that 'prefixfoo' starts not with "prefix".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::stringStartsWith
     * @covers \PhpUnit\Framework\Constraint\StringStartsWith
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintStringStartsNotWith2()
    {
        $constraint = Assert::logicalNot(
            Assert::stringStartsWith('prefix')
        );

        try {
            $constraint->evaluate('prefixfoo', 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
custom message
Failed asserting that 'prefixfoo' starts not with "prefix".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::stringContains
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\Constraint\StringContains
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintStringContains()
    {
        $constraint = Assert::stringContains('foo');

        $this->assertFalse($constraint->evaluate('barbazbar', '', true));
        $this->assertTrue($constraint->evaluate('barfoobar', '', true));
        $this->assertEquals('contains "foo"', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate('barbazbar');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that 'barbazbar' contains "foo".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::stringContains
     * @covers \PhpUnit\Framework\Constraint\StringContains
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintStringContains2()
    {
        $constraint = Assert::stringContains('foo');

        try {
            $constraint->evaluate('barbazbar', 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
custom message
Failed asserting that 'barbazbar' contains "foo".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::stringContains
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\Constraint\StringContains
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintStringNotContains()
    {
        $constraint = Assert::logicalNot(
            Assert::stringContains('foo')
        );

        $this->assertTrue($constraint->evaluate('barbazbar', '', true));
        $this->assertFalse($constraint->evaluate('barfoobar', '', true));
        $this->assertEquals('does not contain "foo"', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate('barfoobar');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that 'barfoobar' does not contain "foo".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::stringContains
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\Constraint\StringContains
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintStringNotContains2()
    {
        $constraint = Assert::logicalNot(
            Assert::stringContains('foo')
        );

        try {
            $constraint->evaluate('barfoobar', 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
custom message
Failed asserting that 'barfoobar' does not contain "foo".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::stringEndsWith
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\Constraint\StringEndsWith
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintStringEndsWith()
    {
        $constraint = Assert::stringEndsWith('suffix');

        $this->assertFalse($constraint->evaluate('foo', '', true));
        $this->assertTrue($constraint->evaluate('foosuffix', '', true));
        $this->assertEquals('ends with "suffix"', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate('foo');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that 'foo' ends with "suffix".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::stringEndsWith
     * @covers \PhpUnit\Framework\Constraint\StringEndsWith
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintStringEndsWith2()
    {
        $constraint = Assert::stringEndsWith('suffix');

        try {
            $constraint->evaluate('foo', 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
custom message
Failed asserting that 'foo' ends with "suffix".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::stringEndsWith
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\Constraint\StringEndsWith
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintStringEndsNotWith()
    {
        $constraint = Assert::logicalNot(
            Assert::stringEndsWith('suffix')
        );

        $this->assertTrue($constraint->evaluate('foo', '', true));
        $this->assertFalse($constraint->evaluate('foosuffix', '', true));
        $this->assertEquals('ends not with "suffix"', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate('foosuffix');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that 'foosuffix' ends not with "suffix".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::stringEndsWith
     * @covers \PhpUnit\Framework\Constraint\StringEndsWith
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintStringEndsNotWith2()
    {
        $constraint = Assert::logicalNot(
            Assert::stringEndsWith('suffix')
        );

        try {
            $constraint->evaluate('foosuffix', 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
custom message
Failed asserting that 'foosuffix' ends not with "suffix".

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Constraint\TraversableContains
     */
    public function testConstraintArrayContainsCheckForObjectIdentity()
    {
        // Check for primitive type.
        $constraint = new Constraint\TraversableContains('foo', true, true);

        $this->assertFalse($constraint->evaluate([0], '', true));
        $this->assertFalse($constraint->evaluate([true], '', true));

        // Default case.
        $constraint = new Constraint\TraversableContains('foo');

        $this->assertTrue($constraint->evaluate([0], '', true));
        $this->assertTrue($constraint->evaluate([true], '', true));
    }

    /**
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\Constraint\TraversableContains
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintArrayContains()
    {
        $constraint = new Constraint\TraversableContains('foo');

        $this->assertFalse($constraint->evaluate(['bar'], '', true));
        $this->assertTrue($constraint->evaluate(['foo'], '', true));
        $this->assertEquals("contains 'foo'", $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate(['bar']);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that an array contains 'foo'.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Constraint\TraversableContains
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintArrayContains2()
    {
        $constraint = new Constraint\TraversableContains('foo');

        try {
            $constraint->evaluate(['bar'], 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
custom message
Failed asserting that an array contains 'foo'.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\Constraint\TraversableContains
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintArrayNotContains()
    {
        $constraint = Assert::logicalNot(
          new Constraint\TraversableContains('foo')
        );

        $this->assertTrue($constraint->evaluate(['bar'], '', true));
        $this->assertFalse($constraint->evaluate(['foo'], '', true));
        $this->assertEquals("does not contain 'foo'", $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate(['foo']);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that an array does not contain 'foo'.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\Constraint\TraversableContains
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintArrayNotContains2()
    {
        $constraint = Assert::logicalNot(
          new Constraint\TraversableContains('foo')
        );

        try {
            $constraint->evaluate(['foo'], 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
custom message
Failed asserting that an array does not contain 'foo'.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\Constraint\TraversableContains
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintSplObjectStorageContains()
    {
        $object     = new StdClass;
        $constraint = new Constraint\TraversableContains($object);
        $this->assertStringMatchesFormat('contains stdClass Object &%s ()', $constraint->toString());

        $storage = new SplObjectStorage;
        $this->assertFalse($constraint->evaluate($storage, '', true));

        $storage->attach($object);
        $this->assertTrue($constraint->evaluate($storage, '', true));

        try {
            $constraint->evaluate(new SplObjectStorage);
        } catch (ExpectationFailedException $e) {
            $this->assertStringMatchesFormat(
              <<<EOF
Failed asserting that a traversable contains stdClass Object &%x ().

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Constraint\TraversableContains
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintSplObjectStorageContains2()
    {
        $object     = new StdClass;
        $constraint = new Constraint\TraversableContains($object);

        try {
            $constraint->evaluate(new SplObjectStorage, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertStringMatchesFormat(
              <<<EOF
custom message
Failed asserting that a traversable contains stdClass Object &%x ().

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::attributeEqualTo
     * @covers PHPUnit_Framework_Constraint_Attribute
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testAttributeEqualTo()
    {
        $object     = new ClassWithNonPublicAttributes;
        $constraint = Assert::attributeEqualTo('foo', 1);

        $this->assertTrue($constraint->evaluate($object, '', true));
        $this->assertEquals('attribute "foo" is equal to 1', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        $constraint = Assert::attributeEqualTo('foo', 2);

        $this->assertFalse($constraint->evaluate($object, '', true));

        try {
            $constraint->evaluate($object);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that attribute "foo" is equal to 2.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::attributeEqualTo
     * @covers PHPUnit_Framework_Constraint_Attribute
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testAttributeEqualTo2()
    {
        $object     = new ClassWithNonPublicAttributes;
        $constraint = Assert::attributeEqualTo('foo', 2);

        try {
            $constraint->evaluate($object, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
custom message\nFailed asserting that attribute "foo" is equal to 2.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::attributeEqualTo
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers PHPUnit_Framework_Constraint_Attribute
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testAttributeNotEqualTo()
    {
        $object     = new ClassWithNonPublicAttributes;
        $constraint = Assert::logicalNot(
            Assert::attributeEqualTo('foo', 2)
        );

        $this->assertTrue($constraint->evaluate($object, '', true));
        $this->assertEquals('attribute "foo" is not equal to 2', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        $constraint = Assert::logicalNot(
            Assert::attributeEqualTo('foo', 1)
        );

        $this->assertFalse($constraint->evaluate($object, '', true));

        try {
            $constraint->evaluate($object);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that attribute "foo" is not equal to 1.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::attributeEqualTo
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers PHPUnit_Framework_Constraint_Attribute
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testAttributeNotEqualTo2()
    {
        $object     = new ClassWithNonPublicAttributes;
        $constraint = Assert::logicalNot(
            Assert::attributeEqualTo('foo', 1)
        );

        try {
            $constraint->evaluate($object, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
custom message\nFailed asserting that attribute "foo" is not equal to 1.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Constraint::count
     * @covers \PhpUnit\Framework\Constraint\IsEmpty
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintIsEmpty()
    {
        $constraint = new Constraint\IsEmpty;

        $this->assertFalse($constraint->evaluate(['foo'], '', true));
        $this->assertTrue($constraint->evaluate([], '', true));
        $this->assertFalse($constraint->evaluate(new ArrayObject(['foo']), '', true));
        $this->assertTrue($constraint->evaluate(new ArrayObject([]), '', true));
        $this->assertEquals('is empty', $constraint->toString());
        $this->assertEquals(1, count($constraint));

        try {
            $constraint->evaluate(['foo']);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that an array is empty.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Constraint\IsEmpty
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintIsEmpty2()
    {
        $constraint = new Constraint\IsEmpty;

        try {
            $constraint->evaluate(['foo'], 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
custom message\nFailed asserting that an array is empty.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers PHPUnit_Framework_Constraint_Count
     */
    public function testConstraintCountWithAnArray()
    {
        $constraint = new PHPUnit_Framework_Constraint_Count(5);

        $this->assertTrue($constraint->evaluate([1, 2, 3, 4, 5], '', true));
        $this->assertFalse($constraint->evaluate([1, 2, 3, 4], '', true));
    }

    /**
     * @covers PHPUnit_Framework_Constraint_Count
     */
    public function testConstraintCountWithAnIteratorWhichDoesNotImplementCountable()
    {
        $constraint = new PHPUnit_Framework_Constraint_Count(5);

        $this->assertTrue($constraint->evaluate(new TestIterator([1, 2, 3, 4, 5]), '', true));
        $this->assertFalse($constraint->evaluate(new TestIterator([1, 2, 3, 4]), '', true));
    }

    /**
     * @covers PHPUnit_Framework_Constraint_Count
     */
    public function testConstraintCountWithAnObjectImplementingCountable()
    {
        $constraint = new PHPUnit_Framework_Constraint_Count(5);

        $this->assertTrue($constraint->evaluate(new ArrayObject([1, 2, 3, 4, 5]), '', true));
        $this->assertFalse($constraint->evaluate(new ArrayObject([1, 2, 3, 4]), '', true));
    }

    /**
     * @covers PHPUnit_Framework_Constraint_Count
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintCountFailing()
    {
        $constraint = new PHPUnit_Framework_Constraint_Count(5);

        try {
            $constraint->evaluate([1, 2]);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that actual size 2 matches expected size 5.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers PHPUnit_Framework_Constraint_Count
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintNotCountFailing()
    {
        $constraint = Assert::logicalNot(
          new PHPUnit_Framework_Constraint_Count(2)
        );

        try {
            $constraint->evaluate([1, 2]);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that actual size 2 does not match expected size 2.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Constraint\SameSize
     */
    public function testConstraintSameSizeWithAnArray()
    {
        $constraint = new Constraint\SameSize([1, 2, 3, 4, 5]);

        $this->assertTrue($constraint->evaluate([6, 7, 8, 9, 10], '', true));
        $this->assertFalse($constraint->evaluate([1, 2, 3, 4], '', true));
    }

    /**
     * @covers \PhpUnit\Framework\Constraint\SameSize
     */
    public function testConstraintSameSizeWithAnIteratorWhichDoesNotImplementCountable()
    {
        $constraint = new Constraint\SameSize(new TestIterator([1, 2, 3, 4, 5]));

        $this->assertTrue($constraint->evaluate(new TestIterator([6, 7, 8, 9, 10]), '', true));
        $this->assertFalse($constraint->evaluate(new TestIterator([1, 2, 3, 4]), '', true));
    }

    /**
     * @covers \PhpUnit\Framework\Constraint\SameSize
     */
    public function testConstraintSameSizeWithAnObjectImplementingCountable()
    {
        $constraint = new Constraint\SameSize(new ArrayObject([1, 2, 3, 4, 5]));

        $this->assertTrue($constraint->evaluate(new ArrayObject([6, 7, 8, 9, 10]), '', true));
        $this->assertFalse($constraint->evaluate(new ArrayObject([1, 2, 3, 4]), '', true));
    }

    /**
     * @covers \PhpUnit\Framework\Constraint\SameSize
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintSameSizeFailing()
    {
        $constraint = new Constraint\SameSize([1, 2, 3, 4, 5]);

        try {
            $constraint->evaluate([1, 2]);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that actual size 2 matches expected size 5.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers \PhpUnit\Framework\Assert::logicalNot
     * @covers \PhpUnit\Framework\Constraint\LogicalNot
     * @covers \PhpUnit\Framework\Constraint\SameSize
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintNotSameSizeFailing()
    {
        $constraint = Assert::logicalNot(
          new Constraint\SameSize([1, 2])
        );

        try {
            $constraint->evaluate([3, 4]);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that actual size 2 does not match expected size 2.

EOF
              ,
              TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * @covers PHPUnit_Framework_Constraint_Exception
     * @covers \PhpUnit\Framework\TestFailure::exceptionToString
     */
    public function testConstraintException()
    {
        $constraint = new PHPUnit_Framework_Constraint_Exception('FoobarException');
        $exception  = new DummyException('Test');
        $stackTrace = $exception->getTraceAsString();

        try {
            $constraint->evaluate($exception);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
              <<<EOF
Failed asserting that exception of type "DummyException" matches expected exception "FoobarException". Message was: "Test" at
$stackTrace.

EOF
                ,
                TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    /**
     * Removes spaces in front of newlines
     *
     * @param  string $string
     * @return string
     */
    private function trimnl($string)
    {
        return preg_replace('/[ ]*\n/', "\n", $string);
    }
}
