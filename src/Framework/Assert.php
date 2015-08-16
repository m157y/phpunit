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

use ArrayAccess;
use Countable;
use DOMDocument;
use DOMElement;
use PhpUnit\Framework\Constraint;
use PHPUnit_Framework_Constraint_And;
use PHPUnit_Framework_Constraint_ArraySubset;
use PHPUnit_Util_InvalidArgumentHelper;
use PHPUnit_Util_Type;
use PHPUnit_Util_XML;
use ReflectionClass;
use ReflectionException;
use ReflectionObject;
use ReflectionProperty;
use Traversable;

/**
 * A set of assert methods.
 *
 * @since Class available since Release 2.0.0
 */
abstract class Assert
{
    /**
     * @var int
     */
    private static $count = 0;

    /**
     * Asserts that an array has a specified key.
     *
     * @param mixed             $key
     * @param array|\ArrayAccess $array
     * @param string            $message
     * @since Method available since Release 3.0.0
     */
    public static function assertArrayHasKey($key, $array, $message = '')
    {
        if (!(is_integer($key) || is_string($key))) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
                1,
                'integer or string'
            );
        }

        if (!(is_array($array) || $array instanceof ArrayAccess)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
                2,
                'array or ArrayAccess'
            );
        }

        $constraint = new Constraint\ArrayHasKey($key);

        self::assertThat($array, $constraint, $message);
    }

    /**
     * Asserts that an array has a specified subset.
     *
     * @param array|ArrayAccess $subset
     * @param array|ArrayAccess $array
     * @param bool              $strict  Check for object identity
     * @param string            $message
     * @since Method available since Release 4.4.0
     */
    public static function assertArraySubset($subset, $array, $strict = false, $message = '')
    {
        if (!is_array($subset)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
                1,
                'array or ArrayAccess'
            );
        }

        if (!is_array($array)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
                2,
                'array or ArrayAccess'
            );
        }

        $constraint = new PHPUnit_Framework_Constraint_ArraySubset($subset, $strict);

        self::assertThat($array, $constraint, $message);
    }

    /**
     * Asserts that an array does not have a specified key.
     *
     * @param mixed             $key
     * @param array|ArrayAccess $array
     * @param string            $message
     * @since  Method available since Release 3.0.0
     */
    public static function assertArrayNotHasKey($key, $array, $message = '')
    {
        if (!(is_integer($key) || is_string($key))) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
                1,
                'integer or string'
            );
        }

        if (!(is_array($array) || $array instanceof ArrayAccess)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
                2,
                'array or ArrayAccess'
            );
        }

        $constraint = new Constraint\LogicalNot(
            new Constraint\ArrayHasKey($key)
        );

        self::assertThat($array, $constraint, $message);
    }

    /**
     * Asserts that a haystack contains a needle.
     *
     * @param mixed  $needle
     * @param mixed  $haystack
     * @param string $message
     * @param bool   $ignoreCase
     * @param bool   $checkForObjectIdentity
     * @param bool   $checkForNonObjectIdentity
     * @since  Method available since Release 2.1.0
     */
    public static function assertContains($needle, $haystack, $message = '', $ignoreCase = false, $checkForObjectIdentity = true, $checkForNonObjectIdentity = false)
    {
        if (is_array($haystack) ||
            is_object($haystack) && $haystack instanceof Traversable) {
            $constraint = new Constraint\TraversableContains(
                $needle,
                $checkForObjectIdentity,
                $checkForNonObjectIdentity
            );
        } elseif (is_string($haystack)) {
            if (!is_string($needle)) {
                throw PHPUnit_Util_InvalidArgumentHelper::factory(
                    1,
                    'string'
                );
            }

            $constraint = new Constraint\StringContains(
                $needle,
                $ignoreCase
            );
        } else {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
                2,
                'array, traversable or string'
            );
        }

        self::assertThat($haystack, $constraint, $message);
    }

    /**
     * Asserts that a haystack that is stored in a static attribute of a class
     * or an attribute of an object contains a needle.
     *
     * @param mixed  $needle
     * @param string $haystackAttributeName
     * @param mixed  $haystackClassOrObject
     * @param string $message
     * @param bool   $ignoreCase
     * @param bool   $checkForObjectIdentity
     * @param bool   $checkForNonObjectIdentity
     * @since  Method available since Release 3.0.0
     */
    public static function assertAttributeContains($needle, $haystackAttributeName, $haystackClassOrObject, $message = '', $ignoreCase = false, $checkForObjectIdentity = true, $checkForNonObjectIdentity = false)
    {
        self::assertContains(
            $needle,
            self::readAttribute($haystackClassOrObject, $haystackAttributeName),
            $message,
            $ignoreCase,
            $checkForObjectIdentity,
            $checkForNonObjectIdentity
        );
    }

    /**
     * Asserts that a haystack does not contain a needle.
     *
     * @param mixed  $needle
     * @param mixed  $haystack
     * @param string $message
     * @param bool   $ignoreCase
     * @param bool   $checkForObjectIdentity
     * @param bool   $checkForNonObjectIdentity
     * @since  Method available since Release 2.1.0
     */
    public static function assertNotContains($needle, $haystack, $message = '', $ignoreCase = false, $checkForObjectIdentity = true, $checkForNonObjectIdentity = false)
    {
        if (is_array($haystack) ||
            is_object($haystack) && $haystack instanceof Traversable) {
            $constraint = new Constraint\LogicalNot(
                new Constraint\TraversableContains(
                    $needle,
                    $checkForObjectIdentity,
                    $checkForNonObjectIdentity
                )
            );
        } elseif (is_string($haystack)) {
            if (!is_string($needle)) {
                throw PHPUnit_Util_InvalidArgumentHelper::factory(
                    1,
                    'string'
                );
            }

            $constraint = new Constraint\LogicalNot(
                new Constraint\StringContains(
                    $needle,
                    $ignoreCase
                )
            );
        } else {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
                2,
                'array, traversable or string'
            );
        }

        self::assertThat($haystack, $constraint, $message);
    }

    /**
     * Asserts that a haystack that is stored in a static attribute of a class
     * or an attribute of an object does not contain a needle.
     *
     * @param mixed  $needle
     * @param string $haystackAttributeName
     * @param mixed  $haystackClassOrObject
     * @param string $message
     * @param bool   $ignoreCase
     * @param bool   $checkForObjectIdentity
     * @param bool   $checkForNonObjectIdentity
     * @since  Method available since Release 3.0.0
     */
    public static function assertAttributeNotContains($needle, $haystackAttributeName, $haystackClassOrObject, $message = '', $ignoreCase = false, $checkForObjectIdentity = true, $checkForNonObjectIdentity = false)
    {
        self::assertNotContains(
            $needle,
            self::readAttribute($haystackClassOrObject, $haystackAttributeName),
            $message,
            $ignoreCase,
            $checkForObjectIdentity,
            $checkForNonObjectIdentity
        );
    }

    /**
     * Asserts that a haystack contains only values of a given type.
     *
     * @param string $type
     * @param mixed  $haystack
     * @param bool   $isNativeType
     * @param string $message
     * @since  Method available since Release 3.1.4
     */
    public static function assertContainsOnly($type, $haystack, $isNativeType = null, $message = '')
    {
        if (!(is_array($haystack) ||
            is_object($haystack) && $haystack instanceof Traversable)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
                2,
                'array or traversable'
            );
        }

        if ($isNativeType == null) {
            $isNativeType = PHPUnit_Util_Type::isType($type);
        }

        self::assertThat(
            $haystack,
            new Constraint\TraversableContainsOnly(
                $type,
                $isNativeType
            ),
            $message
        );
    }

    /**
     * Asserts that a haystack contains only instances of a given classname
     *
     * @param string            $classname
     * @param array|Traversable $haystack
     * @param string            $message
     */
    public static function assertContainsOnlyInstancesOf($classname, $haystack, $message = '')
    {
        if (!(is_array($haystack) ||
            is_object($haystack) && $haystack instanceof Traversable)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
                2,
                'array or traversable'
            );
        }

        self::assertThat(
            $haystack,
            new Constraint\TraversableContainsOnly(
                $classname,
                false
            ),
            $message
        );
    }

    /**
     * Asserts that a haystack that is stored in a static attribute of a class
     * or an attribute of an object contains only values of a given type.
     *
     * @param string $type
     * @param string $haystackAttributeName
     * @param mixed  $haystackClassOrObject
     * @param bool   $isNativeType
     * @param string $message
     * @since  Method available since Release 3.1.4
     */
    public static function assertAttributeContainsOnly($type, $haystackAttributeName, $haystackClassOrObject, $isNativeType = null, $message = '')
    {
        self::assertContainsOnly(
            $type,
            self::readAttribute($haystackClassOrObject, $haystackAttributeName),
            $isNativeType,
            $message
        );
    }

    /**
     * Asserts that a haystack does not contain only values of a given type.
     *
     * @param string $type
     * @param mixed  $haystack
     * @param bool   $isNativeType
     * @param string $message
     * @since  Method available since Release 3.1.4
     */
    public static function assertNotContainsOnly($type, $haystack, $isNativeType = null, $message = '')
    {
        if (!(is_array($haystack) ||
            is_object($haystack) && $haystack instanceof Traversable)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
                2,
                'array or traversable'
            );
        }

        if ($isNativeType == null) {
            $isNativeType = PHPUnit_Util_Type::isType($type);
        }

        self::assertThat(
            $haystack,
            new Constraint\LogicalNot(
                new Constraint\TraversableContainsOnly(
                    $type,
                    $isNativeType
                )
            ),
            $message
        );
    }

    /**
     * Asserts that a haystack that is stored in a static attribute of a class
     * or an attribute of an object does not contain only values of a given
     * type.
     *
     * @param string $type
     * @param string $haystackAttributeName
     * @param mixed  $haystackClassOrObject
     * @param bool   $isNativeType
     * @param string $message
     * @since  Method available since Release 3.1.4
     */
    public static function assertAttributeNotContainsOnly($type, $haystackAttributeName, $haystackClassOrObject, $isNativeType = null, $message = '')
    {
        self::assertNotContainsOnly(
            $type,
            self::readAttribute($haystackClassOrObject, $haystackAttributeName),
            $isNativeType,
            $message
        );
    }

    /**
     * Asserts the number of elements of an array, Countable or Traversable.
     *
     * @param int    $expectedCount
     * @param mixed  $haystack
     * @param string $message
     */
    public static function assertCount($expectedCount, $haystack, $message = '')
    {
        if (!is_int($expectedCount)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'integer');
        }

        if (!$haystack instanceof Countable &&
            !$haystack instanceof Traversable &&
            !is_array($haystack)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'countable or traversable');
        }

        self::assertThat(
            $haystack,
            new Constraint\Count($expectedCount),
            $message
        );
    }

    /**
     * Asserts the number of elements of an array, Countable or Traversable
     * that is stored in an attribute.
     *
     * @param int    $expectedCount
     * @param string $haystackAttributeName
     * @param mixed  $haystackClassOrObject
     * @param string $message
     * @since Method available since Release 3.6.0
     */
    public static function assertAttributeCount($expectedCount, $haystackAttributeName, $haystackClassOrObject, $message = '')
    {
        self::assertCount(
            $expectedCount,
            self::readAttribute($haystackClassOrObject, $haystackAttributeName),
            $message
        );
    }

    /**
     * Asserts the number of elements of an array, Countable or Traversable.
     *
     * @param int    $expectedCount
     * @param mixed  $haystack
     * @param string $message
     */
    public static function assertNotCount($expectedCount, $haystack, $message = '')
    {
        if (!is_int($expectedCount)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'integer');
        }

        if (!$haystack instanceof Countable &&
            !$haystack instanceof Traversable &&
            !is_array($haystack)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'countable or traversable');
        }

        $constraint = new Constraint\LogicalNot(
            new Constraint\Count($expectedCount)
        );

        self::assertThat($haystack, $constraint, $message);
    }

    /**
     * Asserts the number of elements of an array, Countable or Traversable
     * that is stored in an attribute.
     *
     * @param int    $expectedCount
     * @param string $haystackAttributeName
     * @param mixed  $haystackClassOrObject
     * @param string $message
     * @since Method available since Release 3.6.0
     */
    public static function assertAttributeNotCount($expectedCount, $haystackAttributeName, $haystackClassOrObject, $message = '')
    {
        self::assertNotCount(
            $expectedCount,
            self::readAttribute($haystackClassOrObject, $haystackAttributeName),
            $message
        );
    }

    /**
     * Asserts that two variables are equal.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     * @param float  $delta
     * @param int    $maxDepth
     * @param bool   $canonicalize
     * @param bool   $ignoreCase
     */
    public static function assertEquals($expected, $actual, $message = '', $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false)
    {
        $constraint = new Constraint\IsEqual(
            $expected,
            $delta,
            $maxDepth,
            $canonicalize,
            $ignoreCase
        );

        self::assertThat($actual, $constraint, $message);
    }

    /**
     * Asserts that a variable is equal to an attribute of an object.
     *
     * @param mixed  $expected
     * @param string $actualAttributeName
     * @param string $actualClassOrObject
     * @param string $message
     * @param float  $delta
     * @param int    $maxDepth
     * @param bool   $canonicalize
     * @param bool   $ignoreCase
     */
    public static function assertAttributeEquals($expected, $actualAttributeName, $actualClassOrObject, $message = '', $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false)
    {
        self::assertEquals(
            $expected,
            self::readAttribute($actualClassOrObject, $actualAttributeName),
            $message,
            $delta,
            $maxDepth,
            $canonicalize,
            $ignoreCase
        );
    }

    /**
     * Asserts that two variables are not equal.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     * @param float  $delta
     * @param int    $maxDepth
     * @param bool   $canonicalize
     * @param bool   $ignoreCase
     * @since  Method available since Release 2.3.0
     */
    public static function assertNotEquals($expected, $actual, $message = '', $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false)
    {
        $constraint = new Constraint\LogicalNot(
            new Constraint\IsEqual(
                $expected,
                $delta,
                $maxDepth,
                $canonicalize,
                $ignoreCase
            )
        );

        self::assertThat($actual, $constraint, $message);
    }

    /**
     * Asserts that a variable is not equal to an attribute of an object.
     *
     * @param mixed  $expected
     * @param string $actualAttributeName
     * @param string $actualClassOrObject
     * @param string $message
     * @param float  $delta
     * @param int    $maxDepth
     * @param bool   $canonicalize
     * @param bool   $ignoreCase
     */
    public static function assertAttributeNotEquals($expected, $actualAttributeName, $actualClassOrObject, $message = '', $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false)
    {
        self::assertNotEquals(
            $expected,
            self::readAttribute($actualClassOrObject, $actualAttributeName),
            $message,
            $delta,
            $maxDepth,
            $canonicalize,
            $ignoreCase
        );
    }

    /**
     * Asserts that a variable is empty.
     *
     * @param  mixed                                  $actual
     * @param  string                                 $message
     * @throws \PhpUnit\Framework\AssertionFailedError
     */
    public static function assertEmpty($actual, $message = '')
    {
        self::assertThat($actual, self::isEmpty(), $message);
    }

    /**
     * Asserts that a static attribute of a class or an attribute of an object
     * is empty.
     *
     * @param string $haystackAttributeName
     * @param mixed  $haystackClassOrObject
     * @param string $message
     * @since Method available since Release 3.5.0
     */
    public static function assertAttributeEmpty($haystackAttributeName, $haystackClassOrObject, $message = '')
    {
        self::assertEmpty(
            self::readAttribute($haystackClassOrObject, $haystackAttributeName),
            $message
        );
    }

    /**
     * Asserts that a variable is not empty.
     *
     * @param  mixed                                  $actual
     * @param  string                                 $message
     * @throws \PhpUnit\Framework\AssertionFailedError
     */
    public static function assertNotEmpty($actual, $message = '')
    {
        self::assertThat($actual, self::logicalNot(self::isEmpty()), $message);
    }

    /**
     * Asserts that a static attribute of a class or an attribute of an object
     * is not empty.
     *
     * @param string $haystackAttributeName
     * @param mixed  $haystackClassOrObject
     * @param string $message
     * @since Method available since Release 3.5.0
     */
    public static function assertAttributeNotEmpty($haystackAttributeName, $haystackClassOrObject, $message = '')
    {
        self::assertNotEmpty(
            self::readAttribute($haystackClassOrObject, $haystackAttributeName),
            $message
        );
    }

    /**
     * Asserts that a value is greater than another value.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertGreaterThan($expected, $actual, $message = '')
    {
        self::assertThat($actual, self::greaterThan($expected), $message);
    }

    /**
     * Asserts that an attribute is greater than another value.
     *
     * @param mixed  $expected
     * @param string $actualAttributeName
     * @param string $actualClassOrObject
     * @param string $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertAttributeGreaterThan($expected, $actualAttributeName, $actualClassOrObject, $message = '')
    {
        self::assertGreaterThan(
            $expected,
            self::readAttribute($actualClassOrObject, $actualAttributeName),
            $message
        );
    }

    /**
     * Asserts that a value is greater than or equal to another value.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertGreaterThanOrEqual($expected, $actual, $message = '')
    {
        self::assertThat(
            $actual,
            self::greaterThanOrEqual($expected),
            $message
        );
    }

    /**
     * Asserts that an attribute is greater than or equal to another value.
     *
     * @param mixed  $expected
     * @param string $actualAttributeName
     * @param string $actualClassOrObject
     * @param string $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertAttributeGreaterThanOrEqual($expected, $actualAttributeName, $actualClassOrObject, $message = '')
    {
        self::assertGreaterThanOrEqual(
            $expected,
            self::readAttribute($actualClassOrObject, $actualAttributeName),
            $message
        );
    }

    /**
     * Asserts that a value is smaller than another value.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertLessThan($expected, $actual, $message = '')
    {
        self::assertThat($actual, self::lessThan($expected), $message);
    }

    /**
     * Asserts that an attribute is smaller than another value.
     *
     * @param mixed  $expected
     * @param string $actualAttributeName
     * @param string $actualClassOrObject
     * @param string $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertAttributeLessThan($expected, $actualAttributeName, $actualClassOrObject, $message = '')
    {
        self::assertLessThan(
            $expected,
            self::readAttribute($actualClassOrObject, $actualAttributeName),
            $message
        );
    }

    /**
     * Asserts that a value is smaller than or equal to another value.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertLessThanOrEqual($expected, $actual, $message = '')
    {
        self::assertThat($actual, self::lessThanOrEqual($expected), $message);
    }

    /**
     * Asserts that an attribute is smaller than or equal to another value.
     *
     * @param mixed  $expected
     * @param string $actualAttributeName
     * @param string $actualClassOrObject
     * @param string $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertAttributeLessThanOrEqual($expected, $actualAttributeName, $actualClassOrObject, $message = '')
    {
        self::assertLessThanOrEqual(
            $expected,
            self::readAttribute($actualClassOrObject, $actualAttributeName),
            $message
        );
    }

    /**
     * Asserts that the contents of one file is equal to the contents of another
     * file.
     *
     * @param string $expected
     * @param string $actual
     * @param string $message
     * @param bool   $canonicalize
     * @param bool   $ignoreCase
     * @since  Method available since Release 3.2.14
     */
    public static function assertFileEquals($expected, $actual, $message = '', $canonicalize = false, $ignoreCase = false)
    {
        self::assertFileExists($expected, $message);
        self::assertFileExists($actual, $message);

        self::assertEquals(
            file_get_contents($expected),
            file_get_contents($actual),
            $message,
            0,
            10,
            $canonicalize,
            $ignoreCase
        );
    }

    /**
     * Asserts that the contents of one file is not equal to the contents of
     * another file.
     *
     * @param string $expected
     * @param string $actual
     * @param string $message
     * @param bool   $canonicalize
     * @param bool   $ignoreCase
     * @since  Method available since Release 3.2.14
     */
    public static function assertFileNotEquals($expected, $actual, $message = '', $canonicalize = false, $ignoreCase = false)
    {
        self::assertFileExists($expected, $message);
        self::assertFileExists($actual, $message);

        self::assertNotEquals(
            file_get_contents($expected),
            file_get_contents($actual),
            $message,
            0,
            10,
            $canonicalize,
            $ignoreCase
        );
    }

    /**
     * Asserts that the contents of a string is equal
     * to the contents of a file.
     *
     * @param string $expectedFile
     * @param string $actualString
     * @param string $message
     * @param bool   $canonicalize
     * @param bool   $ignoreCase
     * @since  Method available since Release 3.3.0
     */
    public static function assertStringEqualsFile($expectedFile, $actualString, $message = '', $canonicalize = false, $ignoreCase = false)
    {
        self::assertFileExists($expectedFile, $message);

        self::assertEquals(
            file_get_contents($expectedFile),
            $actualString,
            $message,
            0,
            10,
            $canonicalize,
            $ignoreCase
        );
    }

    /**
     * Asserts that the contents of a string is not equal
     * to the contents of a file.
     *
     * @param string $expectedFile
     * @param string $actualString
     * @param string $message
     * @param bool   $canonicalize
     * @param bool   $ignoreCase
     * @since  Method available since Release 3.3.0
     */
    public static function assertStringNotEqualsFile($expectedFile, $actualString, $message = '', $canonicalize = false, $ignoreCase = false)
    {
        self::assertFileExists($expectedFile, $message);

        self::assertNotEquals(
            file_get_contents($expectedFile),
            $actualString,
            $message,
            0,
            10,
            $canonicalize,
            $ignoreCase
        );
    }

    /**
     * Asserts that a file exists.
     *
     * @param string $filename
     * @param string $message
     * @since  Method available since Release 3.0.0
     */
    public static function assertFileExists($filename, $message = '')
    {
        if (!is_string($filename)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        $constraint = new Constraint\FileExists;

        self::assertThat($filename, $constraint, $message);
    }

    /**
     * Asserts that a file does not exist.
     *
     * @param string $filename
     * @param string $message
     * @since  Method available since Release 3.0.0
     */
    public static function assertFileNotExists($filename, $message = '')
    {
        if (!is_string($filename)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        $constraint = new Constraint\LogicalNot(
            new Constraint\FileExists
        );

        self::assertThat($filename, $constraint, $message);
    }

    /**
     * Asserts that a condition is true.
     *
     * @param  bool                                   $condition
     * @param  string                                 $message
     * @throws \PhpUnit\Framework\AssertionFailedError
     */
    public static function assertTrue($condition, $message = '')
    {
        self::assertThat($condition, self::isTrue(), $message);
    }

    /**
     * Asserts that a condition is not true.
     *
     * @param  bool                                   $condition
     * @param  string                                 $message
     * @throws \PhpUnit\Framework\AssertionFailedError
     */
    public static function assertNotTrue($condition, $message = '')
    {
        self::assertThat($condition, self::logicalNot(self::isTrue()), $message);
    }

    /**
     * Asserts that a condition is false.
     *
     * @param  bool                                   $condition
     * @param  string                                 $message
     * @throws \PhpUnit\Framework\AssertionFailedError
     */
    public static function assertFalse($condition, $message = '')
    {
        self::assertThat($condition, self::isFalse(), $message);
    }

    /**
     * Asserts that a condition is not false.
     *
     * @param  bool                                   $condition
     * @param  string                                 $message
     * @throws \PhpUnit\Framework\AssertionFailedError
     */
    public static function assertNotFalse($condition, $message = '')
    {
        self::assertThat($condition, self::logicalNot(self::isFalse()), $message);
    }

    /**
     * Asserts that a variable is not null.
     *
     * @param mixed  $actual
     * @param string $message
     */
    public static function assertNotNull($actual, $message = '')
    {
        self::assertThat($actual, self::logicalNot(self::isNull()), $message);
    }

    /**
     * Asserts that a variable is null.
     *
     * @param mixed  $actual
     * @param string $message
     */
    public static function assertNull($actual, $message = '')
    {
        self::assertThat($actual, self::isNull(), $message);
    }

    /**
     * Asserts that a variable is finite.
     *
     * @param mixed  $actual
     * @param string $message
     */
    public static function assertFinite($actual, $message = '')
    {
        self::assertThat($actual, self::isFinite(), $message);
    }

    /**
     * Asserts that a variable is infinite.
     *
     * @param mixed  $actual
     * @param string $message
     */
    public static function assertInfinite($actual, $message = '')
    {
        self::assertThat($actual, self::isInfinite(), $message);
    }

    /**
     * Asserts that a variable is nan.
     *
     * @param mixed  $actual
     * @param string $message
     */
    public static function assertNan($actual, $message = '')
    {
        self::assertThat($actual, self::isNan(), $message);
    }

    /**
     * Asserts that a class has a specified attribute.
     *
     * @param string $attributeName
     * @param string $className
     * @param string $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertClassHasAttribute($attributeName, $className, $message = '')
    {
        if (!is_string($attributeName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        if (!preg_match('/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/', $attributeName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'valid attribute name');
        }

        if (!is_string($className) || !class_exists($className)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'class name', $className);
        }

        $constraint = new Constraint\ClassHasAttribute(
            $attributeName
        );

        self::assertThat($className, $constraint, $message);
    }

    /**
     * Asserts that a class does not have a specified attribute.
     *
     * @param string $attributeName
     * @param string $className
     * @param string $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertClassNotHasAttribute($attributeName, $className, $message = '')
    {
        if (!is_string($attributeName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        if (!preg_match('/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/', $attributeName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'valid attribute name');
        }

        if (!is_string($className) || !class_exists($className)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'class name', $className);
        }

        $constraint = new Constraint\LogicalNot(
            new Constraint\ClassHasAttribute($attributeName)
        );

        self::assertThat($className, $constraint, $message);
    }

    /**
     * Asserts that a class has a specified static attribute.
     *
     * @param string $attributeName
     * @param string $className
     * @param string $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertClassHasStaticAttribute($attributeName, $className, $message = '')
    {
        if (!is_string($attributeName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        if (!preg_match('/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/', $attributeName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'valid attribute name');
        }

        if (!is_string($className) || !class_exists($className)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'class name', $className);
        }

        $constraint = new Constraint\ClassHasStaticAttribute(
            $attributeName
        );

        self::assertThat($className, $constraint, $message);
    }

    /**
     * Asserts that a class does not have a specified static attribute.
     *
     * @param string $attributeName
     * @param string $className
     * @param string $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertClassNotHasStaticAttribute($attributeName, $className, $message = '')
    {
        if (!is_string($attributeName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        if (!preg_match('/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/', $attributeName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'valid attribute name');
        }

        if (!is_string($className) || !class_exists($className)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'class name', $className);
        }

        $constraint = new Constraint\LogicalNot(
            new Constraint\ClassHasStaticAttribute(
                $attributeName
            )
        );

        self::assertThat($className, $constraint, $message);
    }

    /**
     * Asserts that an object has a specified attribute.
     *
     * @param string $attributeName
     * @param object $object
     * @param string $message
     * @since  Method available since Release 3.0.0
     */
    public static function assertObjectHasAttribute($attributeName, $object, $message = '')
    {
        if (!is_string($attributeName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        if (!preg_match('/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/', $attributeName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'valid attribute name');
        }

        if (!is_object($object)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'object');
        }

        $constraint = new Constraint\ObjectHasAttribute(
            $attributeName
        );

        self::assertThat($object, $constraint, $message);
    }

    /**
     * Asserts that an object does not have a specified attribute.
     *
     * @param string $attributeName
     * @param object $object
     * @param string $message
     * @since  Method available since Release 3.0.0
     */
    public static function assertObjectNotHasAttribute($attributeName, $object, $message = '')
    {
        if (!is_string($attributeName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        if (!preg_match('/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/', $attributeName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'valid attribute name');
        }

        if (!is_object($object)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'object');
        }

        $constraint = new Constraint\LogicalNot(
            new Constraint\ObjectHasAttribute($attributeName)
        );

        self::assertThat($object, $constraint, $message);
    }

    /**
     * Asserts that two variables have the same type and value.
     * Used on objects, it asserts that two variables reference
     * the same object.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     */
    public static function assertSame($expected, $actual, $message = '')
    {
        if (is_bool($expected) && is_bool($actual)) {
            self::assertEquals($expected, $actual, $message);
        } else {
            $constraint = new Constraint\IsIdentical(
                $expected
            );

            self::assertThat($actual, $constraint, $message);
        }
    }

    /**
     * Asserts that a variable and an attribute of an object have the same type
     * and value.
     *
     * @param mixed  $expected
     * @param string $actualAttributeName
     * @param object $actualClassOrObject
     * @param string $message
     */
    public static function assertAttributeSame($expected, $actualAttributeName, $actualClassOrObject, $message = '')
    {
        self::assertSame(
            $expected,
            self::readAttribute($actualClassOrObject, $actualAttributeName),
            $message
        );
    }

    /**
     * Asserts that two variables do not have the same type and value.
     * Used on objects, it asserts that two variables do not reference
     * the same object.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     */
    public static function assertNotSame($expected, $actual, $message = '')
    {
        if (is_bool($expected) && is_bool($actual)) {
            self::assertNotEquals($expected, $actual, $message);
        } else {
            $constraint = new Constraint\LogicalNot(
                new Constraint\IsIdentical($expected)
            );

            self::assertThat($actual, $constraint, $message);
        }
    }

    /**
     * Asserts that a variable and an attribute of an object do not have the
     * same type and value.
     *
     * @param mixed  $expected
     * @param string $actualAttributeName
     * @param object $actualClassOrObject
     * @param string $message
     */
    public static function assertAttributeNotSame($expected, $actualAttributeName, $actualClassOrObject, $message = '')
    {
        self::assertNotSame(
            $expected,
            self::readAttribute($actualClassOrObject, $actualAttributeName),
            $message
        );
    }

    /**
     * Asserts that a variable is of a given type.
     *
     * @param string $expected
     * @param mixed  $actual
     * @param string $message
     * @since Method available since Release 3.5.0
     */
    public static function assertInstanceOf($expected, $actual, $message = '')
    {
        if (!(is_string($expected) && (class_exists($expected) || interface_exists($expected)))) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'class or interface name');
        }

        $constraint = new Constraint\IsInstanceOf(
            $expected
        );

        self::assertThat($actual, $constraint, $message);
    }

    /**
     * Asserts that an attribute is of a given type.
     *
     * @param string $expected
     * @param string $attributeName
     * @param mixed  $classOrObject
     * @param string $message
     * @since Method available since Release 3.5.0
     */
    public static function assertAttributeInstanceOf($expected, $attributeName, $classOrObject, $message = '')
    {
        self::assertInstanceOf(
            $expected,
            self::readAttribute($classOrObject, $attributeName),
            $message
        );
    }

    /**
     * Asserts that a variable is not of a given type.
     *
     * @param string $expected
     * @param mixed  $actual
     * @param string $message
     * @since Method available since Release 3.5.0
     */
    public static function assertNotInstanceOf($expected, $actual, $message = '')
    {
        if (!(is_string($expected) && (class_exists($expected) || interface_exists($expected)))) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'class or interface name');
        }

        $constraint = new Constraint\LogicalNot(
            new Constraint\IsInstanceOf($expected)
        );

        self::assertThat($actual, $constraint, $message);
    }

    /**
     * Asserts that an attribute is of a given type.
     *
     * @param string $expected
     * @param string $attributeName
     * @param mixed  $classOrObject
     * @param string $message
     * @since Method available since Release 3.5.0
     */
    public static function assertAttributeNotInstanceOf($expected, $attributeName, $classOrObject, $message = '')
    {
        self::assertNotInstanceOf(
            $expected,
            self::readAttribute($classOrObject, $attributeName),
            $message
        );
    }

    /**
     * Asserts that a variable is of a given type.
     *
     * @param string $expected
     * @param mixed  $actual
     * @param string $message
     * @since Method available since Release 3.5.0
     */
    public static function assertInternalType($expected, $actual, $message = '')
    {
        if (!is_string($expected)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        $constraint = new Constraint\IsType(
            $expected
        );

        self::assertThat($actual, $constraint, $message);
    }

    /**
     * Asserts that an attribute is of a given type.
     *
     * @param string $expected
     * @param string $attributeName
     * @param mixed  $classOrObject
     * @param string $message
     * @since Method available since Release 3.5.0
     */
    public static function assertAttributeInternalType($expected, $attributeName, $classOrObject, $message = '')
    {
        self::assertInternalType(
            $expected,
            self::readAttribute($classOrObject, $attributeName),
            $message
        );
    }

    /**
     * Asserts that a variable is not of a given type.
     *
     * @param string $expected
     * @param mixed  $actual
     * @param string $message
     * @since Method available since Release 3.5.0
     */
    public static function assertNotInternalType($expected, $actual, $message = '')
    {
        if (!is_string($expected)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        $constraint = new Constraint\LogicalNot(
            new Constraint\IsType($expected)
        );

        self::assertThat($actual, $constraint, $message);
    }

    /**
     * Asserts that an attribute is of a given type.
     *
     * @param string $expected
     * @param string $attributeName
     * @param mixed  $classOrObject
     * @param string $message
     * @since Method available since Release 3.5.0
     */
    public static function assertAttributeNotInternalType($expected, $attributeName, $classOrObject, $message = '')
    {
        self::assertNotInternalType(
            $expected,
            self::readAttribute($classOrObject, $attributeName),
            $message
        );
    }

    /**
     * Asserts that a string matches a given regular expression.
     *
     * @param string $pattern
     * @param string $string
     * @param string $message
     */
    public static function assertRegExp($pattern, $string, $message = '')
    {
        if (!is_string($pattern)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        if (!is_string($string)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'string');
        }

        $constraint = new Constraint\MatchesRegularExpression($pattern);

        self::assertThat($string, $constraint, $message);
    }

    /**
     * Asserts that a string does not match a given regular expression.
     *
     * @param string $pattern
     * @param string $string
     * @param string $message
     * @since  Method available since Release 2.1.0
     */
    public static function assertNotRegExp($pattern, $string, $message = '')
    {
        if (!is_string($pattern)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        if (!is_string($string)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'string');
        }

        $constraint = new Constraint\LogicalNot(
            new Constraint\MatchesRegularExpression($pattern)
        );

        self::assertThat($string, $constraint, $message);
    }

    /**
     * Assert that the size of two arrays (or `Countable` or `Traversable` objects)
     * is the same.
     *
     * @param array|Countable|Traversable $expected
     * @param array|Countable|Traversable $actual
     * @param string                      $message
     */
    public static function assertSameSize($expected, $actual, $message = '')
    {
        if (!$expected instanceof Countable &&
            !$expected instanceof Traversable &&
            !is_array($expected)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'countable or traversable');
        }

        if (!$actual instanceof Countable &&
            !$actual instanceof Traversable &&
            !is_array($actual)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'countable or traversable');
        }

        self::assertThat(
            $actual,
            new Constraint\SameSize($expected),
            $message
        );
    }

    /**
     * Assert that the size of two arrays (or `Countable` or `Traversable` objects)
     * is not the same.
     *
     * @param array|Countable|Traversable $expected
     * @param array|Countable|Traversable $actual
     * @param string                      $message
     */
    public static function assertNotSameSize($expected, $actual, $message = '')
    {
        if (!$expected instanceof Countable &&
            !$expected instanceof Traversable &&
            !is_array($expected)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'countable or traversable');
        }

        if (!$actual instanceof Countable &&
            !$actual instanceof Traversable &&
            !is_array($actual)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'countable or traversable');
        }

        $constraint = new Constraint\LogicalNot(
            new Constraint\SameSize($expected)
        );

        self::assertThat($actual, $constraint, $message);
    }

    /**
     * Asserts that a string matches a given format string.
     *
     * @param string $format
     * @param string $string
     * @param string $message
     * @since  Method available since Release 3.5.0
     */
    public static function assertStringMatchesFormat($format, $string, $message = '')
    {
        if (!is_string($format)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        if (!is_string($string)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'string');
        }

        $constraint = new Constraint\StringMatches($format);

        self::assertThat($string, $constraint, $message);
    }

    /**
     * Asserts that a string does not match a given format string.
     *
     * @param string $format
     * @param string $string
     * @param string $message
     * @since  Method available since Release 3.5.0
     */
    public static function assertStringNotMatchesFormat($format, $string, $message = '')
    {
        if (!is_string($format)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        if (!is_string($string)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'string');
        }

        $constraint = new Constraint\LogicalNot(
            new Constraint\StringMatches($format)
        );

        self::assertThat($string, $constraint, $message);
    }

    /**
     * Asserts that a string matches a given format file.
     *
     * @param string $formatFile
     * @param string $string
     * @param string $message
     * @since  Method available since Release 3.5.0
     */
    public static function assertStringMatchesFormatFile($formatFile, $string, $message = '')
    {
        self::assertFileExists($formatFile, $message);

        if (!is_string($string)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'string');
        }

        $constraint = new Constraint\StringMatches(
            file_get_contents($formatFile)
        );

        self::assertThat($string, $constraint, $message);
    }

    /**
     * Asserts that a string does not match a given format string.
     *
     * @param string $formatFile
     * @param string $string
     * @param string $message
     * @since  Method available since Release 3.5.0
     */
    public static function assertStringNotMatchesFormatFile($formatFile, $string, $message = '')
    {
        self::assertFileExists($formatFile, $message);

        if (!is_string($string)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'string');
        }

        $constraint = new Constraint\LogicalNot(
            new Constraint\StringMatches(
                file_get_contents($formatFile)
            )
        );

        self::assertThat($string, $constraint, $message);
    }

    /**
     * Asserts that a string starts with a given prefix.
     *
     * @param string $prefix
     * @param string $string
     * @param string $message
     * @since  Method available since Release 3.4.0
     */
    public static function assertStringStartsWith($prefix, $string, $message = '')
    {
        if (!is_string($prefix)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        if (!is_string($string)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'string');
        }

        $constraint = new Constraint\StringStartsWith(
            $prefix
        );

        self::assertThat($string, $constraint, $message);
    }

    /**
     * Asserts that a string starts not with a given prefix.
     *
     * @param string $prefix
     * @param string $string
     * @param string $message
     * @since  Method available since Release 3.4.0
     */
    public static function assertStringStartsNotWith($prefix, $string, $message = '')
    {
        if (!is_string($prefix)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        if (!is_string($string)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'string');
        }

        $constraint = new Constraint\LogicalNot(
            new Constraint\StringStartsWith($prefix)
        );

        self::assertThat($string, $constraint, $message);
    }

    /**
     * Asserts that a string ends with a given suffix.
     *
     * @param string $suffix
     * @param string $string
     * @param string $message
     * @since  Method available since Release 3.4.0
     */
    public static function assertStringEndsWith($suffix, $string, $message = '')
    {
        if (!is_string($suffix)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        if (!is_string($string)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'string');
        }

        $constraint = new Constraint\StringEndsWith($suffix);

        self::assertThat($string, $constraint, $message);
    }

    /**
     * Asserts that a string ends not with a given suffix.
     *
     * @param string $suffix
     * @param string $string
     * @param string $message
     * @since  Method available since Release 3.4.0
     */
    public static function assertStringEndsNotWith($suffix, $string, $message = '')
    {
        if (!is_string($suffix)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        if (!is_string($string)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'string');
        }

        $constraint = new Constraint\LogicalNot(
            new Constraint\StringEndsWith($suffix)
        );

        self::assertThat($string, $constraint, $message);
    }

    /**
     * Asserts that two XML files are equal.
     *
     * @param string $expectedFile
     * @param string $actualFile
     * @param string $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertXmlFileEqualsXmlFile($expectedFile, $actualFile, $message = '')
    {
        self::assertFileExists($expectedFile);
        self::assertFileExists($actualFile);

        $expected                     = new DOMDocument;
        $expected->preserveWhiteSpace = false;
        $expected->load($expectedFile);

        $actual                     = new DOMDocument;
        $actual->preserveWhiteSpace = false;
        $actual->load($actualFile);

        self::assertEquals($expected, $actual, $message);
    }

    /**
     * Asserts that two XML files are not equal.
     *
     * @param string $expectedFile
     * @param string $actualFile
     * @param string $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertXmlFileNotEqualsXmlFile($expectedFile, $actualFile, $message = '')
    {
        self::assertFileExists($expectedFile);
        self::assertFileExists($actualFile);

        $expected                     = new DOMDocument;
        $expected->preserveWhiteSpace = false;
        $expected->load($expectedFile);

        $actual                     = new DOMDocument;
        $actual->preserveWhiteSpace = false;
        $actual->load($actualFile);

        self::assertNotEquals($expected, $actual, $message);
    }

    /**
     * Asserts that two XML documents are equal.
     *
     * @param string $expectedFile
     * @param string $actualXml
     * @param string $message
     * @since  Method available since Release 3.3.0
     */
    public static function assertXmlStringEqualsXmlFile($expectedFile, $actualXml, $message = '')
    {
        self::assertFileExists($expectedFile);

        $expected                     = new DOMDocument;
        $expected->preserveWhiteSpace = false;
        $expected->load($expectedFile);

        $actual                     = new DOMDocument;
        $actual->preserveWhiteSpace = false;
        $actual->loadXML($actualXml);

        self::assertEquals($expected, $actual, $message);
    }

    /**
     * Asserts that two XML documents are not equal.
     *
     * @param string $expectedFile
     * @param string $actualXml
     * @param string $message
     * @since  Method available since Release 3.3.0
     */
    public static function assertXmlStringNotEqualsXmlFile($expectedFile, $actualXml, $message = '')
    {
        self::assertFileExists($expectedFile);

        $expected                     = new DOMDocument;
        $expected->preserveWhiteSpace = false;
        $expected->load($expectedFile);

        $actual                     = new DOMDocument;
        $actual->preserveWhiteSpace = false;
        $actual->loadXML($actualXml);

        self::assertNotEquals($expected, $actual, $message);
    }

    /**
     * Asserts that two XML documents are equal.
     *
     * @param string $expectedXml
     * @param string $actualXml
     * @param string $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertXmlStringEqualsXmlString($expectedXml, $actualXml, $message = '')
    {
        $expected                     = new DOMDocument;
        $expected->preserveWhiteSpace = false;
        $expected->loadXML($expectedXml);

        $actual                     = new DOMDocument;
        $actual->preserveWhiteSpace = false;
        $actual->loadXML($actualXml);

        self::assertEquals($expected, $actual, $message);
    }

    /**
     * Asserts that two XML documents are not equal.
     *
     * @param string $expectedXml
     * @param string $actualXml
     * @param string $message
     * @since  Method available since Release 3.1.0
     */
    public static function assertXmlStringNotEqualsXmlString($expectedXml, $actualXml, $message = '')
    {
        $expected                     = new DOMDocument;
        $expected->preserveWhiteSpace = false;
        $expected->loadXML($expectedXml);

        $actual                     = new DOMDocument;
        $actual->preserveWhiteSpace = false;
        $actual->loadXML($actualXml);

        self::assertNotEquals($expected, $actual, $message);
    }

    /**
     * Asserts that a hierarchy of DOMElements matches.
     *
     * @param DOMElement $expectedElement
     * @param DOMElement $actualElement
     * @param bool       $checkAttributes
     * @param string     $message
     * @since  Method available since Release 3.3.0
     */
    public static function assertEqualXMLStructure(DOMElement $expectedElement, DOMElement $actualElement, $checkAttributes = false, $message = '')
    {
        $expectedElement = clone $expectedElement;
        $actualElement   = clone $actualElement;

        self::assertEquals(
            $expectedElement->tagName,
            $actualElement->tagName,
            $message
        );

        if ($checkAttributes) {
            self::assertEquals(
                $expectedElement->attributes->length,
                $actualElement->attributes->length,
                sprintf(
                    '%s%sNumber of attributes on node "%s" does not match',
                    $message,
                    !empty($message) ? "\n" : '',
                    $expectedElement->tagName
                )
            );

            for ($i = 0; $i < $expectedElement->attributes->length; $i++) {
                $expectedAttribute = $expectedElement->attributes->item($i);
                $actualAttribute   = $actualElement->attributes->getNamedItem(
                    $expectedAttribute->name
                );

                if (!$actualAttribute) {
                    self::fail(
                        sprintf(
                            '%s%sCould not find attribute "%s" on node "%s"',
                            $message,
                            !empty($message) ? "\n" : '',
                            $expectedAttribute->name,
                            $expectedElement->tagName
                        )
                    );
                }
            }
        }

        PHPUnit_Util_XML::removeCharacterDataNodes($expectedElement);
        PHPUnit_Util_XML::removeCharacterDataNodes($actualElement);

        self::assertEquals(
            $expectedElement->childNodes->length,
            $actualElement->childNodes->length,
            sprintf(
                '%s%sNumber of child nodes of "%s" differs',
                $message,
                !empty($message) ? "\n" : '',
                $expectedElement->tagName
            )
        );

        for ($i = 0; $i < $expectedElement->childNodes->length; $i++) {
            self::assertEqualXMLStructure(
                $expectedElement->childNodes->item($i),
                $actualElement->childNodes->item($i),
                $checkAttributes,
                $message
            );
        }
    }

    /**
     * Evaluates a PhpUnit\Framework\Constraint matcher object.
     *
     * @param mixed                         $value
     * @param \PhpUnit\Framework\Constraint $constraint
     * @param string                        $message
     * @since  Method available since Release 3.0.0
     */
    public static function assertThat($value, Constraint $constraint, $message = '')
    {
        self::$count += count($constraint);

        $constraint->evaluate($value, $message);
    }

    /**
     * Asserts that a string is a valid JSON string.
     *
     * @param string $actualJson
     * @param string $message
     * @since  Method available since Release 3.7.20
     */
    public static function assertJson($actualJson, $message = '')
    {
        if (!is_string($actualJson)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        self::assertThat($actualJson, self::isJson(), $message);
    }

    /**
     * Asserts that two given JSON encoded objects or arrays are equal.
     *
     * @param string $expectedJson
     * @param string $actualJson
     * @param string $message
     */
    public static function assertJsonStringEqualsJsonString($expectedJson, $actualJson, $message = '')
    {
        self::assertJson($expectedJson, $message);
        self::assertJson($actualJson, $message);

        $expected = json_decode($expectedJson);
        $actual   = json_decode($actualJson);

        self::assertEquals($expected, $actual, $message);
    }

    /**
     * Asserts that two given JSON encoded objects or arrays are not equal.
     *
     * @param string $expectedJson
     * @param string $actualJson
     * @param string $message
     */
    public static function assertJsonStringNotEqualsJsonString($expectedJson, $actualJson, $message = '')
    {
        self::assertJson($expectedJson, $message);
        self::assertJson($actualJson, $message);

        $expected = json_decode($expectedJson);
        $actual   = json_decode($actualJson);

        self::assertNotEquals($expected, $actual, $message);
    }

    /**
     * Asserts that the generated JSON encoded object and the content of the given file are equal.
     *
     * @param string $expectedFile
     * @param string $actualJson
     * @param string $message
     */
    public static function assertJsonStringEqualsJsonFile($expectedFile, $actualJson, $message = '')
    {
        self::assertFileExists($expectedFile, $message);
        $expectedJson = file_get_contents($expectedFile);

        self::assertJson($expectedJson, $message);
        self::assertJson($actualJson, $message);

        // call constraint
        $constraint = new Constraint\JsonMatches($expectedJson);

        self::assertThat($actualJson, $constraint, $message);
    }

    /**
     * Asserts that the generated JSON encoded object and the content of the given file are not equal.
     *
     * @param string $expectedFile
     * @param string $actualJson
     * @param string $message
     */
    public static function assertJsonStringNotEqualsJsonFile($expectedFile, $actualJson, $message = '')
    {
        self::assertFileExists($expectedFile, $message);
        $expectedJson = file_get_contents($expectedFile);

        self::assertJson($expectedJson, $message);
        self::assertJson($actualJson, $message);

        // call constraint
        $constraint = new Constraint\JsonMatches($expectedJson);

        self::assertThat($actualJson, new Constraint\LogicalNot($constraint), $message);
    }

    /**
     * Asserts that two JSON files are not equal.
     *
     * @param string $expectedFile
     * @param string $actualFile
     * @param string $message
     */
    public static function assertJsonFileNotEqualsJsonFile($expectedFile, $actualFile, $message = '')
    {
        self::assertFileExists($expectedFile, $message);
        self::assertFileExists($actualFile, $message);

        $actualJson   = file_get_contents($actualFile);
        $expectedJson = file_get_contents($expectedFile);

        self::assertJson($expectedJson, $message);
        self::assertJson($actualJson, $message);

        // call constraint
        $constraintExpected = new Constraint\JsonMatches($expectedJson);
        $constraintActual = new Constraint\JsonMatches($actualJson);

        self::assertThat($expectedJson, new Constraint\LogicalNot($constraintActual), $message);
        self::assertThat($actualJson, new Constraint\LogicalNot($constraintExpected), $message);
    }

    /**
     * Asserts that two JSON files are equal.
     *
     * @param string $expectedFile
     * @param string $actualFile
     * @param string $message
     */
    public static function assertJsonFileEqualsJsonFile($expectedFile, $actualFile, $message = '')
    {
        self::assertFileExists($expectedFile, $message);
        self::assertFileExists($actualFile, $message);

        $actualJson   = file_get_contents($actualFile);
        $expectedJson = file_get_contents($expectedFile);

        self::assertJson($expectedJson, $message);
        self::assertJson($actualJson, $message);

        // call constraint
        $constraintExpected = new Constraint\JsonMatches($expectedJson);
        $constraintActual = new Constraint\JsonMatches($actualJson);

        self::assertThat($expectedJson, $constraintActual, $message);
        self::assertThat($actualJson, $constraintExpected, $message);
    }

    /**
     * Returns a PHPUnit_Framework_Constraint_And matcher object.
     *
     * @return PHPUnit_Framework_Constraint_And
     * @since  Method available since Release 3.0.0
     */
    public static function logicalAnd()
    {
        $constraints = func_get_args();

        $constraint = new PHPUnit_Framework_Constraint_And;
        $constraint->setConstraints($constraints);

        return $constraint;
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\LogicalOr matcher object.
     *
     * @return \PhpUnit\Framework\Constraint\LogicalOr
     * @since  Method available since Release 3.0.0
     */
    public static function logicalOr()
    {
        $constraints = func_get_args();

        $constraint = new Constraint\LogicalOr;
        $constraint->setConstraints($constraints);

        return $constraint;
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\LogicalNot matcher object.
     *
     * @param  \PhpUnit\Framework\Constraint     $constraint
     * @return \PhpUnit\Framework\Constraint\LogicalNot
     * @since  Method available since Release 3.0.0
     */
    public static function logicalNot(Constraint $constraint)
    {
        return new Constraint\LogicalNot($constraint);
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\LogicalXor matcher object.
     *
     * @return \PhpUnit\Framework\Constraint\LogicalXor
     * @since  Method available since Release 3.0.0
     */
    public static function logicalXor()
    {
        $constraints = func_get_args();

        $constraint = new Constraint\LogicalXor;
        $constraint->setConstraints($constraints);

        return $constraint;
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\Anything matcher object.
     *
     * @return \PhpUnit\Framework\Constraint\Anything
     * @since  Method available since Release 3.0.0
     */
    public static function anything()
    {
        return new Constraint\Anything;
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\IsTrue matcher object.
     *
     * @return \PhpUnit\Framework\Constraint\IsTrue
     * @since  Method available since Release 3.3.0
     */
    public static function isTrue()
    {
        return new Constraint\IsTrue;
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\Callback matcher object.
     *
     * @param  callable                              $callback
     * @return \PhpUnit\Framework\Constraint\Callback
     */
    public static function callback($callback)
    {
        return new Constraint\Callback($callback);
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\IsFalse matcher object.
     *
     * @return \PhpUnit\Framework\Constraint\IsFalse
     * @since  Method available since Release 3.3.0
     */
    public static function isFalse()
    {
        return new Constraint\IsFalse;
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\IsJson matcher object.
     *
     * @return \PhpUnit\Framework\Constraint\IsJson
     * @since  Method available since Release 3.7.20
     */
    public static function isJson()
    {
        return new Constraint\IsJson;
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\IsNull matcher object.
     *
     * @return \PhpUnit\Framework\Constraint\IsNull
     * @since  Method available since Release 3.3.0
     */
    public static function isNull()
    {
        return new Constraint\IsNull;
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\IsFinite matcher object.
     *
     * @return \PhpUnit\Framework\Constraint\IsFinite
     * @since  Method available since Release 5.0.0
     */
    public static function isFinite()
    {
        return new Constraint\IsFinite;
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\IsInfinite matcher object.
     *
     * @return \PhpUnit\Framework\Constraint\IsInfinite
     * @since  Method available since Release 5.0.0
     */
    public static function isInfinite()
    {
        return new Constraint\IsInfinite;
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\IsNan matcher object.
     *
     * @return \PhpUnit\Framework\Constraint\IsNan
     * @since  Method available since Release 5.0.0
     */
    public static function isNan()
    {
        return new Constraint\IsNan;
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\Attribute matcher object.
     *
     * @param  \PhpUnit\Framework\Constraint          $constraint
     * @param  string                                 $attributeName
     * @return \PhpUnit\Framework\Constraint\Attribute
     * @since  Method available since Release 3.1.0
     */
    public static function attribute(Constraint $constraint, $attributeName)
    {
        return new Constraint\Attribute(
            $constraint,
            $attributeName
        );
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\TraversableContains matcher
     * object.
     *
     * @param  mixed                                            $value
     * @param  bool                                             $checkForObjectIdentity
     * @param  bool                                             $checkForNonObjectIdentity
     * @return \PhpUnit\Framework\Constraint\TraversableContains
     * @since  Method available since Release 3.0.0
     */
    public static function contains($value, $checkForObjectIdentity = true, $checkForNonObjectIdentity = false)
    {
        return new Constraint\TraversableContains($value, $checkForObjectIdentity, $checkForNonObjectIdentity);
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\TraversableContainsOnly matcher
     * object.
     *
     * @param  string                                               $type
     * @return \PhpUnit\Framework\Constraint\TraversableContainsOnly
     * @since  Method available since Release 3.1.4
     */
    public static function containsOnly($type)
    {
        return new Constraint\TraversableContainsOnly($type);
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\TraversableContainsOnly matcher
     * object.
     *
     * @param  string                                               $classname
     * @return \PhpUnit\Framework\Constraint\TraversableContainsOnly
     */
    public static function containsOnlyInstancesOf($classname)
    {
        return new Constraint\TraversableContainsOnly($classname, false);
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\ArrayHasKey matcher object.
     *
     * @param  mixed                                    $key
     * @return \PhpUnit\Framework\Constraint\ArrayHasKey
     * @since  Method available since Release 3.0.0
     */
    public static function arrayHasKey($key)
    {
        return new Constraint\ArrayHasKey($key);
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\IsEqual matcher object.
     *
     * @param  mixed                                $value
     * @param  float                                $delta
     * @param  int                                  $maxDepth
     * @param  bool                                 $canonicalize
     * @param  bool                                 $ignoreCase
     * @return \PhpUnit\Framework\Constraint\IsEqual
     * @since  Method available since Release 3.0.0
     */
    public static function equalTo($value, $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false)
    {
        return new Constraint\IsEqual(
            $value,
            $delta,
            $maxDepth,
            $canonicalize,
            $ignoreCase
        );
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\IsEqual matcher object
     * that is wrapped in a PhpUnit\Framework\Constraint\Attribute matcher
     * object.
     *
     * @param  string                                 $attributeName
     * @param  mixed                                  $value
     * @param  float                                  $delta
     * @param  int                                    $maxDepth
     * @param  bool                                   $canonicalize
     * @param  bool                                   $ignoreCase
     * @return \PhpUnit\Framework\Constraint\Attribute
     * @since  Method available since Release 3.1.0
     */
    public static function attributeEqualTo($attributeName, $value, $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false)
    {
        return self::attribute(
            self::equalTo(
                $value,
                $delta,
                $maxDepth,
                $canonicalize,
                $ignoreCase
            ),
            $attributeName
        );
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\IsEmpty matcher object.
     *
     * @return \PhpUnit\Framework\Constraint\IsEmpty
     * @since  Method available since Release 3.5.0
     */
    public static function isEmpty()
    {
        return new Constraint\IsEmpty;
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\FileExists matcher object.
     *
     * @return \PhpUnit\Framework\Constraint\FileExists
     * @since  Method available since Release 3.0.0
     */
    public static function fileExists()
    {
        return new Constraint\FileExists;
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\GreaterThan matcher object.
     *
     * @param  mixed                                    $value
     * @return \PhpUnit\Framework\Constraint\GreaterThan
     * @since  Method available since Release 3.0.0
     */
    public static function greaterThan($value)
    {
        return new Constraint\GreaterThan($value);
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\LogicalOr matcher object that wraps
     * a PhpUnit\Framework\Constraint\IsEqual and a
     * PhpUnit\Framework\Constraint\GreaterThan matcher object.
     *
     * @param  mixed                           $value
     * @return \PhpUnit\Framework\Constraint\LogicalOr
     * @since  Method available since Release 3.1.0
     */
    public static function greaterThanOrEqual($value)
    {
        return self::logicalOr(
            new Constraint\IsEqual($value),
            new Constraint\GreaterThan($value)
        );
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\ClassHasAttribute matcher object.
     *
     * @param  string                                         $attributeName
     * @return \PhpUnit\Framework\Constraint\ClassHasAttribute
     * @since  Method available since Release 3.1.0
     */
    public static function classHasAttribute($attributeName)
    {
        return new Constraint\ClassHasAttribute(
            $attributeName
        );
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\ClassHasStaticAttribute matcher
     * object.
     *
     * @param  string                                               $attributeName
     * @return \PhpUnit\Framework\Constraint\ClassHasStaticAttribute
     * @since  Method available since Release 3.1.0
     */
    public static function classHasStaticAttribute($attributeName)
    {
        return new Constraint\ClassHasStaticAttribute(
            $attributeName
        );
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\ObjectHasAttribute matcher object.
     *
     * @param  string                                          $attributeName
     * @return \PhpUnit\Framework\Constraint\ObjectHasAttribute
     * @since  Method available since Release 3.0.0
     */
    public static function objectHasAttribute($attributeName)
    {
        return new Constraint\ObjectHasAttribute(
            $attributeName
        );
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\IsIdentical matcher object.
     *
     * @param  mixed                                    $value
     * @return \PhpUnit\Framework\Constraint\IsIdentical
     * @since  Method available since Release 3.0.0
     */
    public static function identicalTo($value)
    {
        return new Constraint\IsIdentical($value);
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\IsInstanceOf matcher object.
     *
     * @param  string                                    $className
     * @return \PhpUnit\Framework\Constraint\IsInstanceOf
     * @since  Method available since Release 3.0.0
     */
    public static function isInstanceOf($className)
    {
        return new Constraint\IsInstanceOf($className);
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\IsType matcher object.
     *
     * @param  string                              $type
     * @return \PhpUnit\Framework\Constraint\IsType
     * @since  Method available since Release 3.0.0
     */
    public static function isType($type)
    {
        return new Constraint\IsType($type);
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\LessThan matcher object.
     *
     * @param  mixed                                 $value
     * @return \PhpUnit\Framework\Constraint\LessThan
     * @since  Method available since Release 3.0.0
     */
    public static function lessThan($value)
    {
        return new Constraint\LessThan($value);
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\LogicalOr matcher object that wraps
     * a PhpUnit\Framework\Constraint\IsEqual and a
     * PhpUnit\Framework\Constraint\LessThan matcher object.
     *
     * @param  mixed                           $value
     * @return \PhpUnit\Framework\Constraint\LogicalOr
     * @since  Method available since Release 3.1.0
     */
    public static function lessThanOrEqual($value)
    {
        return self::logicalOr(
            new Constraint\IsEqual($value),
            new Constraint\LessThan($value)
        );
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\MatchesRegularExpression matcher object.
     *
     * @param  string                                 $pattern
     * @return \PhpUnit\Framework\Constraint\MatchesRegularExpression
     * @since  Method available since Release 3.0.0
     */
    public static function matchesRegularExpression($pattern)
    {
        return new Constraint\MatchesRegularExpression($pattern);
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\StringMatches matcher object.
     *
     * @param  string                                     $string
     * @return \PhpUnit\Framework\Constraint\StringMatches
     * @since  Method available since Release 3.5.0
     */
    public static function matches($string)
    {
        return new Constraint\StringMatches($string);
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\StringStartsWith matcher object.
     *
     * @param  mixed                                         $prefix
     * @return \PhpUnit\Framework\Constraint\StringStartsWith
     * @since  Method available since Release 3.4.0
     */
    public static function stringStartsWith($prefix)
    {
        return new Constraint\StringStartsWith($prefix);
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\StringContains matcher object.
     *
     * @param  string                                      $string
     * @param  bool                                        $case
     * @return \PhpUnit\Framework\Constraint\StringContains
     * @since  Method available since Release 3.0.0
     */
    public static function stringContains($string, $case = true)
    {
        return new Constraint\StringContains($string, $case);
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\StringEndsWith matcher object.
     *
     * @param  mixed                                       $suffix
     * @return \PhpUnit\Framework\Constraint\StringEndsWith
     * @since  Method available since Release 3.4.0
     */
    public static function stringEndsWith($suffix)
    {
        return new Constraint\StringEndsWith($suffix);
    }

    /**
     * Returns a PhpUnit\Framework\Constraint\Count matcher object.
     *
     * @param  int                                $count
     * @return \PhpUnit\Framework\Constraint\Count
     */
    public static function countOf($count)
    {
        return new Constraint\Count($count);
    }
    /**
     * Fails a test with the given message.
     *
     * @param  string                                 $message
     * @throws \PhpUnit\Framework\AssertionFailedError
     */
    public static function fail($message = '')
    {
        throw new AssertionFailedError($message);
    }

    /**
     * Returns the value of an attribute of a class or an object.
     * This also works for attributes that are declared protected or private.
     *
     * @param  mixed                       $classOrObject
     * @param  string                      $attributeName
     * @return mixed
     * @throws \PhpUnit\Framework\Exception
     */
    public static function readAttribute($classOrObject, $attributeName)
    {
        if (!is_string($attributeName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'string');
        }

        if (!preg_match('/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/', $attributeName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'valid attribute name');
        }

        if (is_string($classOrObject)) {
            if (!class_exists($classOrObject)) {
                throw PHPUnit_Util_InvalidArgumentHelper::factory(
                    1,
                    'class name'
                );
            }

            return self::getStaticAttribute(
                $classOrObject,
                $attributeName
            );
        } elseif (is_object($classOrObject)) {
            return self::getObjectAttribute(
                $classOrObject,
                $attributeName
            );
        } else {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(
                1,
                'class name or object'
            );
        }
    }

    /**
     * Returns the value of a static attribute.
     * This also works for attributes that are declared protected or private.
     *
     * @param  string                      $className
     * @param  string                      $attributeName
     * @return mixed
     * @throws \PhpUnit\Framework\Exception
     * @since  Method available since Release 4.0.0
     */
    public static function getStaticAttribute($className, $attributeName)
    {
        if (!is_string($className)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        if (!class_exists($className)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'class name');
        }

        if (!is_string($attributeName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'string');
        }

        if (!preg_match('/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/', $attributeName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'valid attribute name');
        }

        $class = new ReflectionClass($className);

        while ($class) {
            $attributes = $class->getStaticProperties();

            if (array_key_exists($attributeName, $attributes)) {
                return $attributes[$attributeName];
            }

            $class = $class->getParentClass();
        }

        throw new Exception(
            sprintf(
                'Attribute "%s" not found in class.',
                $attributeName
            )
        );
    }

    /**
     * Returns the value of an object's attribute.
     * This also works for attributes that are declared protected or private.
     *
     * @param  object                      $object
     * @param  string                      $attributeName
     * @return mixed
     * @throws \PhpUnit\Framework\Exception
     * @since  Method available since Release 4.0.0
     */
    public static function getObjectAttribute($object, $attributeName)
    {
        if (!is_object($object)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'object');
        }

        if (!is_string($attributeName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'string');
        }

        if (!preg_match('/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/', $attributeName)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(2, 'valid attribute name');
        }

        try {
            $attribute = new ReflectionProperty($object, $attributeName);
        } catch (ReflectionException $e) {
            $reflector = new ReflectionObject($object);

            while ($reflector = $reflector->getParentClass()) {
                try {
                    $attribute = $reflector->getProperty($attributeName);
                    break;
                } catch (ReflectionException $e) {
                }
            }
        }

        if (isset($attribute)) {
            if (!$attribute || $attribute->isPublic()) {
                return $object->$attributeName;
            }

            $attribute->setAccessible(true);
            $value = $attribute->getValue($object);
            $attribute->setAccessible(false);

            return $value;
        }

        throw new Exception(
            sprintf(
                'Attribute "%s" not found in object.',
                $attributeName
            )
        );
    }

    /**
     * Mark the test as incomplete.
     *
     * @param  string                                $message
     * @throws \PhpUnit\Framework\IncompleteTestError
     * @since  Method available since Release 3.0.0
     */
    public static function markTestIncomplete($message = '')
    {
        throw new IncompleteTestError($message);
    }

    /**
     * Mark the test as skipped.
     *
     * @param  string                             $message
     * @throws \PhpUnit\Framework\SkippedTestError
     * @since  Method available since Release 3.0.0
     */
    public static function markTestSkipped($message = '')
    {
        throw new SkippedTestError($message);
    }

    /**
     * Return the current assertion count.
     *
     * @return int
     * @since  Method available since Release 3.3.3
     */
    public static function getCount()
    {
        return self::$count;
    }

    /**
     * Reset the assertion counter.
     *
     * @since  Method available since Release 3.3.3
     */
    public static function resetCount()
    {
        self::$count = 0;
    }
}
