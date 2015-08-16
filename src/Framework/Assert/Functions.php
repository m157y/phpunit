<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PhpUnit\Framework\Constraint;

/**
 * Returns a matcher that matches when the method is executed
 * zero or more times.
 *
 * @return PHPUnit_Framework_MockObject_Matcher_AnyInvokedCount
 * @since  Method available since Release 3.0.0
 */
function any()
{
    return call_user_func_array(
        'PhpUnit\\Framework\\TestCase::any',
        func_get_args()
    );
}

/**
 * Returns a PHPUnit_Framework_Constraint_IsAnything matcher object.
 *
 * @return PHPUnit_Framework_Constraint_IsAnything
 * @since  Method available since Release 3.0.0
 */
function anything()
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::anything',
        func_get_args()
    );
}

/**
 * Returns a PHPUnit_Framework_Constraint_ArrayHasKey matcher object.
 *
 * @param  mixed                                    $key
 * @return PHPUnit_Framework_Constraint_ArrayHasKey
 * @since  Method available since Release 3.0.0
 */
function arrayHasKey($key)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::arrayHasKey',
        func_get_args()
    );
}

/**
 * Asserts that an array has a specified key.
 *
 * @param mixed             $key
 * @param array|ArrayAccess $array
 * @param string            $message
 * @since  Method available since Release 3.0.0
 */
function assertArrayHasKey($key, $array, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertArrayHasKey',
        func_get_args()
    );
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
function assertArraySubset($subset, $array, $strict = false, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertArraySubset',
        func_get_args()
    );
}

/**
 * Asserts that an array does not have a specified key.
 *
 * @param mixed             $key
 * @param array|ArrayAccess $array
 * @param string            $message
 * @since  Method available since Release 3.0.0
 */
function assertArrayNotHasKey($key, $array, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertArrayNotHasKey',
        func_get_args()
    );
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
function assertAttributeContains($needle, $haystackAttributeName, $haystackClassOrObject, $message = '', $ignoreCase = false, $checkForObjectIdentity = true, $checkForNonObjectIdentity = false)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertAttributeContains',
        func_get_args()
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
function assertAttributeContainsOnly($type, $haystackAttributeName, $haystackClassOrObject, $isNativeType = null, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertAttributeContainsOnly',
        func_get_args()
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
function assertAttributeCount($expectedCount, $haystackAttributeName, $haystackClassOrObject, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertAttributeCount',
        func_get_args()
    );
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
function assertAttributeEmpty($haystackAttributeName, $haystackClassOrObject, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertAttributeEmpty',
        func_get_args()
    );
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
function assertAttributeEquals($expected, $actualAttributeName, $actualClassOrObject, $message = '', $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertAttributeEquals',
        func_get_args()
    );
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
function assertAttributeGreaterThan($expected, $actualAttributeName, $actualClassOrObject, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertAttributeGreaterThan',
        func_get_args()
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
function assertAttributeGreaterThanOrEqual($expected, $actualAttributeName, $actualClassOrObject, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertAttributeGreaterThanOrEqual',
        func_get_args()
    );
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
function assertAttributeInstanceOf($expected, $attributeName, $classOrObject, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertAttributeInstanceOf',
        func_get_args()
    );
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
function assertAttributeInternalType($expected, $attributeName, $classOrObject, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertAttributeInternalType',
        func_get_args()
    );
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
function assertAttributeLessThan($expected, $actualAttributeName, $actualClassOrObject, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertAttributeLessThan',
        func_get_args()
    );
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
function assertAttributeLessThanOrEqual($expected, $actualAttributeName, $actualClassOrObject, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertAttributeLessThanOrEqual',
        func_get_args()
    );
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
function assertAttributeNotContains($needle, $haystackAttributeName, $haystackClassOrObject, $message = '', $ignoreCase = false, $checkForObjectIdentity = true, $checkForNonObjectIdentity = false)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertAttributeNotContains',
        func_get_args()
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
function assertAttributeNotContainsOnly($type, $haystackAttributeName, $haystackClassOrObject, $isNativeType = null, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertAttributeNotContainsOnly',
        func_get_args()
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
function assertAttributeNotCount($expectedCount, $haystackAttributeName, $haystackClassOrObject, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertAttributeNotCount',
        func_get_args()
    );
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
function assertAttributeNotEmpty($haystackAttributeName, $haystackClassOrObject, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertAttributeNotEmpty',
        func_get_args()
    );
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
function assertAttributeNotEquals($expected, $actualAttributeName, $actualClassOrObject, $message = '', $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertAttributeNotEquals',
        func_get_args()
    );
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
function assertAttributeNotInstanceOf($expected, $attributeName, $classOrObject, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertAttributeNotInstanceOf',
        func_get_args()
    );
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
function assertAttributeNotInternalType($expected, $attributeName, $classOrObject, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertAttributeNotInternalType',
        func_get_args()
    );
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
function assertAttributeNotSame($expected, $actualAttributeName, $actualClassOrObject, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertAttributeNotSame',
        func_get_args()
    );
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
function assertAttributeSame($expected, $actualAttributeName, $actualClassOrObject, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertAttributeSame',
        func_get_args()
    );
}

/**
 * Asserts that a class has a specified attribute.
 *
 * @param string $attributeName
 * @param string $className
 * @param string $message
 * @since  Method available since Release 3.1.0
 */
function assertClassHasAttribute($attributeName, $className, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertClassHasAttribute',
        func_get_args()
    );
}

/**
 * Asserts that a class has a specified static attribute.
 *
 * @param string $attributeName
 * @param string $className
 * @param string $message
 * @since  Method available since Release 3.1.0
 */
function assertClassHasStaticAttribute($attributeName, $className, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertClassHasStaticAttribute',
        func_get_args()
    );
}

/**
 * Asserts that a class does not have a specified attribute.
 *
 * @param string $attributeName
 * @param string $className
 * @param string $message
 * @since  Method available since Release 3.1.0
 */
function assertClassNotHasAttribute($attributeName, $className, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertClassNotHasAttribute',
        func_get_args()
    );
}

/**
 * Asserts that a class does not have a specified static attribute.
 *
 * @param string $attributeName
 * @param string $className
 * @param string $message
 * @since  Method available since Release 3.1.0
 */
function assertClassNotHasStaticAttribute($attributeName, $className, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertClassNotHasStaticAttribute',
        func_get_args()
    );
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
function assertContains($needle, $haystack, $message = '', $ignoreCase = false, $checkForObjectIdentity = true, $checkForNonObjectIdentity = false)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertContains',
        func_get_args()
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
function assertContainsOnly($type, $haystack, $isNativeType = null, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertContainsOnly',
        func_get_args()
    );
}

/**
 * Asserts that a haystack contains only instances of a given classname
 *
 * @param string            $classname
 * @param array|Traversable $haystack
 * @param string            $message
 */
function assertContainsOnlyInstancesOf($classname, $haystack, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertContainsOnlyInstancesOf',
        func_get_args()
    );
}

/**
 * Asserts the number of elements of an array, Countable or Traversable.
 *
 * @param int    $expectedCount
 * @param mixed  $haystack
 * @param string $message
 */
function assertCount($expectedCount, $haystack, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertCount',
        func_get_args()
    );
}

/**
 * Asserts that a variable is empty.
 *
 * @param  mixed                                  $actual
 * @param  string                                 $message
 * @throws \PhpUnit\Framework\AssertionFailedError
 */
function assertEmpty($actual, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertEmpty',
        func_get_args()
    );
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
function assertEqualXMLStructure(DOMElement $expectedElement, DOMElement $actualElement, $checkAttributes = false, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertEqualXMLStructure',
        func_get_args()
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
function assertEquals($expected, $actual, $message = '', $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertEquals',
        func_get_args()
    );
}

/**
 * Asserts that a condition is not true.
 *
 * @param  bool                                   $condition
 * @param  string                                 $message
 * @throws \PhpUnit\Framework\AssertionFailedError
 */
function assertNotTrue($condition, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertNotTrue',
        func_get_args()
    );
}

/**
 * Asserts that a condition is false.
 *
 * @param  bool                                   $condition
 * @param  string                                 $message
 * @throws \PhpUnit\Framework\AssertionFailedError
 */
function assertFalse($condition, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertFalse',
        func_get_args()
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
function assertFileEquals($expected, $actual, $message = '', $canonicalize = false, $ignoreCase = false)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertFileEquals',
        func_get_args()
    );
}

/**
 * Asserts that a file exists.
 *
 * @param string $filename
 * @param string $message
 * @since  Method available since Release 3.0.0
 */
function assertFileExists($filename, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertFileExists',
        func_get_args()
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
function assertFileNotEquals($expected, $actual, $message = '', $canonicalize = false, $ignoreCase = false)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertFileNotEquals',
        func_get_args()
    );
}

/**
 * Asserts that a file does not exist.
 *
 * @param string $filename
 * @param string $message
 * @since  Method available since Release 3.0.0
 */
function assertFileNotExists($filename, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertFileNotExists',
        func_get_args()
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
function assertGreaterThan($expected, $actual, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertGreaterThan',
        func_get_args()
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
function assertGreaterThanOrEqual($expected, $actual, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertGreaterThanOrEqual',
        func_get_args()
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
function assertInstanceOf($expected, $actual, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertInstanceOf',
        func_get_args()
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
function assertInternalType($expected, $actual, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertInternalType',
        func_get_args()
    );
}

/**
 * Asserts that a string is a valid JSON string.
 *
 * @param string $filename
 * @param string $message
 * @since  Method available since Release 3.7.20
 */
function assertJson($expectedJson, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertJson',
        func_get_args()
    );
}

/**
 * Asserts that two JSON files are equal.
 *
 * @param string $expectedFile
 * @param string $actualFile
 * @param string $message
 */
function assertJsonFileEqualsJsonFile($expectedFile, $actualFile, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertJsonFileEqualsJsonFile',
        func_get_args()
    );
}

/**
 * Asserts that two JSON files are not equal.
 *
 * @param string $expectedFile
 * @param string $actualFile
 * @param string $message
 */
function assertJsonFileNotEqualsJsonFile($expectedFile, $actualFile, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertJsonFileNotEqualsJsonFile',
        func_get_args()
    );
}

/**
 * Asserts that the generated JSON encoded object and the content of the given file are equal.
 *
 * @param string $expectedFile
 * @param string $actualJson
 * @param string $message
 */
function assertJsonStringEqualsJsonFile($expectedFile, $actualJson, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertJsonStringEqualsJsonFile',
        func_get_args()
    );
}

/**
 * Asserts that two given JSON encoded objects or arrays are equal.
 *
 * @param string $expectedJson
 * @param string $actualJson
 * @param string $message
 */
function assertJsonStringEqualsJsonString($expectedJson, $actualJson, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertJsonStringEqualsJsonString',
        func_get_args()
    );
}

/**
 * Asserts that the generated JSON encoded object and the content of the given file are not equal.
 *
 * @param string $expectedFile
 * @param string $actualJson
 * @param string $message
 */
function assertJsonStringNotEqualsJsonFile($expectedFile, $actualJson, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertJsonStringNotEqualsJsonFile',
        func_get_args()
    );
}

/**
 * Asserts that two given JSON encoded objects or arrays are not equal.
 *
 * @param string $expectedJson
 * @param string $actualJson
 * @param string $message
 */
function assertJsonStringNotEqualsJsonString($expectedJson, $actualJson, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertJsonStringNotEqualsJsonString',
        func_get_args()
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
function assertLessThan($expected, $actual, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertLessThan',
        func_get_args()
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
function assertLessThanOrEqual($expected, $actual, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertLessThanOrEqual',
        func_get_args()
    );
}

/**
 * Asserts that a variable is finite.
 *
 * @param mixed  $actual
 * @param string $message
 */
function assertFinite($actual, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertFinite',
        func_get_args()
    );
}

/**
 * Asserts that a variable is infinite.
 *
 * @param mixed  $actual
 * @param string $message
 */
function assertInfinite($actual, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertInfinite',
        func_get_args()
    );
}

/**
 * Asserts that a variable is nan.
 *
 * @param mixed  $actual
 * @param string $message
 */
function assertNan($actual, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertNan',
        func_get_args()
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
function assertNotContains($needle, $haystack, $message = '', $ignoreCase = false, $checkForObjectIdentity = true, $checkForNonObjectIdentity = false)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertNotContains',
        func_get_args()
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
function assertNotContainsOnly($type, $haystack, $isNativeType = null, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertNotContainsOnly',
        func_get_args()
    );
}

/**
 * Asserts the number of elements of an array, Countable or Traversable.
 *
 * @param int    $expectedCount
 * @param mixed  $haystack
 * @param string $message
 */
function assertNotCount($expectedCount, $haystack, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertNotCount',
        func_get_args()
    );
}

/**
 * Asserts that a variable is not empty.
 *
 * @param  mixed                                  $actual
 * @param  string                                 $message
 * @throws \PhpUnit\Framework\AssertionFailedError
 */
function assertNotEmpty($actual, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertNotEmpty',
        func_get_args()
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
function assertNotEquals($expected, $actual, $message = '', $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertNotEquals',
        func_get_args()
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
function assertNotInstanceOf($expected, $actual, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertNotInstanceOf',
        func_get_args()
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
function assertNotInternalType($expected, $actual, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertNotInternalType',
        func_get_args()
    );
}

/**
 * Asserts that a condition is not false.
 *
 * @param  bool                                   $condition
 * @param  string                                 $message
 * @throws \PhpUnit\Framework\AssertionFailedError
 */
function assertNotFalse($condition, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertNotFalse',
        func_get_args()
    );
}

/**
 * Asserts that a variable is not null.
 *
 * @param mixed  $actual
 * @param string $message
 */
function assertNotNull($actual, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertNotNull',
        func_get_args()
    );
}

/**
 * Asserts that a string does not match a given regular expression.
 *
 * @param string $pattern
 * @param string $string
 * @param string $message
 * @since  Method available since Release 2.1.0
 */
function assertNotRegExp($pattern, $string, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertNotRegExp',
        func_get_args()
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
function assertNotSame($expected, $actual, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertNotSame',
        func_get_args()
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
function assertNotSameSize($expected, $actual, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertNotSameSize',
        func_get_args()
    );
}

/**
 * Asserts that a variable is null.
 *
 * @param mixed  $actual
 * @param string $message
 */
function assertNull($actual, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertNull',
        func_get_args()
    );
}

/**
 * Asserts that an object has a specified attribute.
 *
 * @param string $attributeName
 * @param object $object
 * @param string $message
 * @since  Method available since Release 3.0.0
 */
function assertObjectHasAttribute($attributeName, $object, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertObjectHasAttribute',
        func_get_args()
    );
}

/**
 * Asserts that an object does not have a specified attribute.
 *
 * @param string $attributeName
 * @param object $object
 * @param string $message
 * @since  Method available since Release 3.0.0
 */
function assertObjectNotHasAttribute($attributeName, $object, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertObjectNotHasAttribute',
        func_get_args()
    );
}

/**
 * Asserts that a string matches a given regular expression.
 *
 * @param string $pattern
 * @param string $string
 * @param string $message
 */
function assertRegExp($pattern, $string, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertRegExp',
        func_get_args()
    );
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
function assertSame($expected, $actual, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertSame',
        func_get_args()
    );
}

/**
 * Assert that the size of two arrays (or `Countable` or `Traversable` objects)
 * is the same.
 *
 * @param array|Countable|Traversable $expected
 * @param array|Countable|Traversable $actual
 * @param string                      $message
 */
function assertSameSize($expected, $actual, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertSameSize',
        func_get_args()
    );
}

/**
 * Asserts that a string ends not with a given prefix.
 *
 * @param string $suffix
 * @param string $string
 * @param string $message
 * @since  Method available since Release 3.4.0
 */
function assertStringEndsNotWith($suffix, $string, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertStringEndsNotWith',
        func_get_args()
    );
}

/**
 * Asserts that a string ends with a given prefix.
 *
 * @param string $suffix
 * @param string $string
 * @param string $message
 * @since  Method available since Release 3.4.0
 */
function assertStringEndsWith($suffix, $string, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertStringEndsWith',
        func_get_args()
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
function assertStringEqualsFile($expectedFile, $actualString, $message = '', $canonicalize = false, $ignoreCase = false)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertStringEqualsFile',
        func_get_args()
    );
}

/**
 * Asserts that a string matches a given format string.
 *
 * @param string $format
 * @param string $string
 * @param string $message
 * @since  Method available since Release 3.5.0
 */
function assertStringMatchesFormat($format, $string, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertStringMatchesFormat',
        func_get_args()
    );
}

/**
 * Asserts that a string matches a given format file.
 *
 * @param string $formatFile
 * @param string $string
 * @param string $message
 * @since  Method available since Release 3.5.0
 */
function assertStringMatchesFormatFile($formatFile, $string, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertStringMatchesFormatFile',
        func_get_args()
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
function assertStringNotEqualsFile($expectedFile, $actualString, $message = '', $canonicalize = false, $ignoreCase = false)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertStringNotEqualsFile',
        func_get_args()
    );
}

/**
 * Asserts that a string does not match a given format string.
 *
 * @param string $format
 * @param string $string
 * @param string $message
 * @since  Method available since Release 3.5.0
 */
function assertStringNotMatchesFormat($format, $string, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertStringNotMatchesFormat',
        func_get_args()
    );
}

/**
 * Asserts that a string does not match a given format string.
 *
 * @param string $formatFile
 * @param string $string
 * @param string $message
 * @since  Method available since Release 3.5.0
 */
function assertStringNotMatchesFormatFile($formatFile, $string, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertStringNotMatchesFormatFile',
        func_get_args()
    );
}

/**
 * Asserts that a string starts not with a given prefix.
 *
 * @param string $prefix
 * @param string $string
 * @param string $message
 * @since  Method available since Release 3.4.0
 */
function assertStringStartsNotWith($prefix, $string, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertStringStartsNotWith',
        func_get_args()
    );
}

/**
 * Asserts that a string starts with a given prefix.
 *
 * @param string $prefix
 * @param string $string
 * @param string $message
 * @since  Method available since Release 3.4.0
 */
function assertStringStartsWith($prefix, $string, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertStringStartsWith',
        func_get_args()
    );
}

/**
 * Evaluates a PhpUnit\Framework\Constraint matcher object.
 *
 * @param  mixed$value
 * @param \PhpUnit\Framework\Constraint $constraint
 * @param string                        $message
 * @since  Method available since Release 3.0.0
 */
function assertThat($value, Constraint $constraint, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertThat',
        func_get_args()
    );
}

/**
 * Asserts that a condition is true.
 *
 * @param  bool                                   $condition
 * @param  string                                 $message
 * @throws \PhpUnit\Framework\AssertionFailedError
 */
function assertTrue($condition, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertTrue',
        func_get_args()
    );
}

/**
 * Asserts that two XML files are equal.
 *
 * @param string $expectedFile
 * @param string $actualFile
 * @param string $message
 * @since  Method available since Release 3.1.0
 */
function assertXmlFileEqualsXmlFile($expectedFile, $actualFile, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertXmlFileEqualsXmlFile',
        func_get_args()
    );
}

/**
 * Asserts that two XML files are not equal.
 *
 * @param string $expectedFile
 * @param string $actualFile
 * @param string $message
 * @since  Method available since Release 3.1.0
 */
function assertXmlFileNotEqualsXmlFile($expectedFile, $actualFile, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertXmlFileNotEqualsXmlFile',
        func_get_args()
    );
}

/**
 * Asserts that two XML documents are equal.
 *
 * @param string $expectedFile
 * @param string $actualXml
 * @param string $message
 * @since  Method available since Release 3.3.0
 */
function assertXmlStringEqualsXmlFile($expectedFile, $actualXml, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertXmlStringEqualsXmlFile',
        func_get_args()
    );
}

/**
 * Asserts that two XML documents are equal.
 *
 * @param string $expectedXml
 * @param string $actualXml
 * @param string $message
 * @since  Method available since Release 3.1.0
 */
function assertXmlStringEqualsXmlString($expectedXml, $actualXml, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertXmlStringEqualsXmlString',
        func_get_args()
    );
}

/**
 * Asserts that two XML documents are not equal.
 *
 * @param string $expectedFile
 * @param string $actualXml
 * @param string $message
 * @since  Method available since Release 3.3.0
 */
function assertXmlStringNotEqualsXmlFile($expectedFile, $actualXml, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertXmlStringNotEqualsXmlFile',
        func_get_args()
    );
}

/**
 * Asserts that two XML documents are not equal.
 *
 * @param string $expectedXml
 * @param string $actualXml
 * @param string $message
 * @since  Method available since Release 3.1.0
 */
function assertXmlStringNotEqualsXmlString($expectedXml, $actualXml, $message = '')
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::assertXmlStringNotEqualsXmlString',
        func_get_args()
    );
}

/**
 * Returns a matcher that matches when the method is executed
 * at the given $index.
 *
 * @param  int                                                 $index
 * @return PHPUnit_Framework_MockObject_Matcher_InvokedAtIndex
 * @since  Method available since Release 3.0.0
 */
function at($index)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\TestCase::at',
        func_get_args()
    );
}

/**
 * Returns a matcher that matches when the method is executed at least once.
 *
 * @return PHPUnit_Framework_MockObject_Matcher_InvokedAtLeastOnce
 * @since  Method available since Release 3.0.0
 */
function atLeastOnce()
{
    return call_user_func_array(
        'PhpUnit\\Framework\\TestCase::atLeastOnce',
        func_get_args()
    );
}

/**
 * Returns a PHPUnit_Framework_Constraint_Attribute matcher object.
 *
 * @param  \PhpUnit\Framework\Constraint          $constraint
 * @param  string                                 $attributeName
 * @return PHPUnit_Framework_Constraint_Attribute
 * @since  Method available since Release 3.1.0
 */
function attribute(Constraint $constraint, $attributeName)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::attribute',
        func_get_args()
    );
}

/**
 * Returns a PHPUnit_Framework_Constraint_IsEqual matcher object
 * that is wrapped in a PHPUnit_Framework_Constraint_Attribute matcher
 * object.
 *
 * @param  string                                 $attributeName
 * @param  mixed                                  $value
 * @param  float                                  $delta
 * @param  int                                    $maxDepth
 * @param  bool                                   $canonicalize
 * @param  bool                                   $ignoreCase
 * @return PHPUnit_Framework_Constraint_Attribute
 * @since  Method available since Release 3.1.0
 */
function attributeEqualTo($attributeName, $value, $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::attributeEqualTo',
        func_get_args()
    );
}

/**
 * Returns a PHPUnit_Framework_Constraint_Callback matcher object.
 *
 * @param  callable                              $callback
 * @return PHPUnit_Framework_Constraint_Callback
 */
function callback($callback)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::callback',
        func_get_args()
    );
}

/**
 * Returns a PHPUnit_Framework_Constraint_ClassHasAttribute matcher object.
 *
 * @param  string                                         $attributeName
 * @return PHPUnit_Framework_Constraint_ClassHasAttribute
 * @since  Method available since Release 3.1.0
 */
function classHasAttribute($attributeName)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::classHasAttribute',
        func_get_args()
    );
}

/**
 * Returns a PHPUnit_Framework_Constraint_ClassHasStaticAttribute matcher
 * object.
 *
 * @param  string                                               $attributeName
 * @return PHPUnit_Framework_Constraint_ClassHasStaticAttribute
 * @since  Method available since Release 3.1.0
 */
function classHasStaticAttribute($attributeName)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::classHasStaticAttribute',
        func_get_args()
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
function contains($value, $checkForObjectIdentity = true, $checkForNonObjectIdentity = false)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::contains',
        func_get_args()
    );
}

/**
 * Returns a PhpUnit\Framework\Constraint\TraversableContainsOnly matcher
 * object.
 *
 * @param  string                                               $type
 * @return \PhpUnit\Framework\Constraint\TraversableContainsOnly
 * @since  Method available since Release 3.1.4
 */
function containsOnly($type)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::containsOnly',
        func_get_args()
    );
}

/**
 * Returns a PhpUnit\Framework\Constraint\TraversableContainsOnly matcher
 * object.
 *
 * @param  string                                               $classname
 * @return \PhpUnit\Framework\Constraint\TraversableContainsOnly
 */
function containsOnlyInstancesOf($classname)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::containsOnlyInstancesOf',
        func_get_args()
    );
}

/**
 * Returns a PHPUnit_Framework_Constraint_IsEqual matcher object.
 *
 * @param  mixed                                $value
 * @param  float                                $delta
 * @param  int                                  $maxDepth
 * @param  bool                                 $canonicalize
 * @param  bool                                 $ignoreCase
 * @return PHPUnit_Framework_Constraint_IsEqual
 * @since  Method available since Release 3.0.0
 */
function equalTo($value, $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::equalTo',
        func_get_args()
    );
}

/**
 * Returns a matcher that matches when the method is executed
 * exactly $count times.
 *
 * @param  int                                               $count
 * @return PHPUnit_Framework_MockObject_Matcher_InvokedCount
 * @since  Method available since Release 3.0.0
 */
function exactly($count)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\TestCase::exactly',
        func_get_args()
    );
}

/**
 * Returns a PHPUnit_Framework_Constraint_FileExists matcher object.
 *
 * @return PHPUnit_Framework_Constraint_FileExists
 * @since  Method available since Release 3.0.0
 */
function fileExists()
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::fileExists',
        func_get_args()
    );
}

/**
 * Returns a PHPUnit_Framework_Constraint_GreaterThan matcher object.
 *
 * @param  mixed                                    $value
 * @return PHPUnit_Framework_Constraint_GreaterThan
 * @since  Method available since Release 3.0.0
 */
function greaterThan($value)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::greaterThan',
        func_get_args()
    );
}

/**
 * Returns a PhpUnit\Framework\Constraint\LogicalOr matcher object that wraps
 * a PHPUnit_Framework_Constraint_IsEqual and a
 * PHPUnit_Framework_Constraint_GreaterThan matcher object.
 *
 * @param  mixed                           $value
 * @return \PhpUnit\Framework\Constraint\LogicalOr
 * @since  Method available since Release 3.1.0
 */
function greaterThanOrEqual($value)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::greaterThanOrEqual',
        func_get_args()
    );
}

/**
 * Returns a PHPUnit_Framework_Constraint_IsIdentical matcher object.
 *
 * @param  mixed                                    $value
 * @return PHPUnit_Framework_Constraint_IsIdentical
 * @since  Method available since Release 3.0.0
 */
function identicalTo($value)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::identicalTo',
        func_get_args()
    );
}

/**
 * Returns a PHPUnit_Framework_Constraint_IsEmpty matcher object.
 *
 * @return PHPUnit_Framework_Constraint_IsEmpty
 * @since  Method available since Release 3.5.0
 */
function isEmpty()
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::isEmpty',
        func_get_args()
    );
}

/**
 * Returns a PHPUnit_Framework_Constraint_IsFalse matcher object.
 *
 * @return PHPUnit_Framework_Constraint_IsFalse
 * @since  Method available since Release 3.3.0
 */
function isFalse()
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::isFalse',
        func_get_args()
    );
}

/**
 * Returns a PhpUnit\Framework\Constraint\IsInstanceOf matcher object.
 *
 * @param  string                                    $className
 * @return \PhpUnit\Framework\Constraint\IsInstanceOf
 * @since  Method available since Release 3.0.0
 */
function isInstanceOf($className)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::isInstanceOf',
        func_get_args()
    );
}

/**
 * Returns a PhpUnit\Framework\Constraint\IsJson matcher object.
 *
 * @return \PhpUnit\Framework\Constraint\IsJson
 * @since  Method available since Release 3.7.20
 */
function isJson()
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::isJson',
        func_get_args()
    );
}

/**
 * Returns a PhpUnit\Framework\Constraint\IsNull matcher object.
 *
 * @return \PhpUnit\Framework\Constraint\IsNull
 * @since  Method available since Release 3.3.0
 */
function isNull()
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::isNull',
        func_get_args()
    );
}

/**
 * Returns a PhpUnit\Framework\Constraint\IsTrue matcher object.
 *
 * @return \PhpUnit\Framework\Constraint\IsTrue
 * @since  Method available since Release 3.3.0
 */
function isTrue()
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::isTrue',
        func_get_args()
    );
}

/**
 * Returns a PhpUnit\Framework\Constraint\IsType matcher object.
 *
 * @param  string                              $type
 * @return \PhpUnit\Framework\Constraint\IsType
 * @since  Method available since Release 3.0.0
 */
function isType($type)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::isType',
        func_get_args()
    );
}

/**
 * Returns a PhpUnit\Framework\Constraint\LessThan matcher object.
 *
 * @param  mixed                                 $value
 * @return \PhpUnit\Framework\Constraint\LessThan
 * @since  Method available since Release 3.0.0
 */
function lessThan($value)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::lessThan',
        func_get_args()
    );
}

/**
 * Returns a PhpUnit\Framework\Constraint\LogicalOr matcher object that wraps
 * a PHPUnit_Framework_Constraint_IsEqual and a
 * PhpUnit\Framework\Constraint\LessThan matcher object.
 *
 * @param  mixed                           $value
 * @return \PhpUnit\Framework\Constraint\LogicalOr
 * @since  Method available since Release 3.1.0
 */
function lessThanOrEqual($value)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::lessThanOrEqual',
        func_get_args()
    );
}

/**
 * Returns a PHPUnit_Framework_Constraint_And matcher object.
 *
 * @return PHPUnit_Framework_Constraint_And
 * @since  Method available since Release 3.0.0
 */
function logicalAnd()
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::logicalAnd',
        func_get_args()
    );
}

/**
 * Returns a PhpUnit\Framework\Constraint\LogicalNot matcher object.
 *
 * @param  \PhpUnit\Framework\Constraint    $constraint
 * @return \PhpUnit\Framework\Constraint\LogicalNot
 * @since  Method available since Release 3.0.0
 */
function logicalNot(Constraint $constraint)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::logicalNot',
        func_get_args()
    );
}

/**
 * Returns a PhpUnit\Framework\Constraint\LogicalOr matcher object.
 *
 * @return \PhpUnit\Framework\Constraint\LogicalOr
 * @since  Method available since Release 3.0.0
 */
function logicalOr()
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::logicalOr',
        func_get_args()
    );
}

/**
 * Returns a PhpUnit\Framework\Constraint\LogicalXor matcher object.
 *
 * @return \PhpUnit\Framework\Constraint\LogicalXor
 * @since  Method available since Release 3.0.0
 */
function logicalXor()
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::logicalXor',
        func_get_args()
    );
}

/**
 * Returns a PhpUnit\Framework\Constraint\StringMatches matcher object.
 *
 * @param  string                                     $string
 * @return \PhpUnit\Framework\Constraint\StringMatches
 * @since  Method available since Release 3.5.0
 */
function matches($string)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::matches',
        func_get_args()
    );
}

/**
 * Returns a PhpUnit\Framework\Constraint\MatchesRegularExpression matcher object.
 *
 * @param  string                                 $pattern
 * @return \PhpUnit\Framework\Constraint\MatchesRegularExpression
 * @since  Method available since Release 3.0.0
 */
function matchesRegularExpression($pattern)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::matchesRegularExpression',
        func_get_args()
    );
}

/**
 * Returns a matcher that matches when the method is never executed.
 *
 * @return PHPUnit_Framework_MockObject_Matcher_InvokedCount
 * @since  Method available since Release 3.0.0
 */
function never()
{
    return call_user_func_array(
        'PhpUnit\\Framework\\TestCase::never',
        func_get_args()
    );
}

/**
 * Returns a PhpUnit\Framework\Constraint\ObjectHasAttribute matcher object.
 *
 * @param  string                                          $attributeName
 * @return \PhpUnit\Framework\Constraint\ObjectHasAttribute
 * @since  Method available since Release 3.0.0
 */
function objectHasAttribute($attributeName)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::objectHasAttribute',
        func_get_args()
    );
}

/**
 * @param  mixed                                              $value, ...
 * @return PHPUnit_Framework_MockObject_Stub_ConsecutiveCalls
 * @since  Method available since Release 3.0.0
 */
function onConsecutiveCalls()
{
    return call_user_func_array(
        'PhpUnit\\Framework\\TestCase::onConsecutiveCalls',
        func_get_args()
    );
}

/**
 * Returns a matcher that matches when the method is executed exactly once.
 *
 * @return PHPUnit_Framework_MockObject_Matcher_InvokedCount
 * @since  Method available since Release 3.0.0
 */
function once()
{
    return call_user_func_array(
        'PhpUnit\\Framework\\TestCase::once',
        func_get_args()
    );
}

/**
 * @param  int                                              $argumentIndex
 * @return PHPUnit_Framework_MockObject_Stub_ReturnArgument
 * @since  Method available since Release 3.3.0
 */
function returnArgument($argumentIndex)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\TestCase::returnArgument',
        func_get_args()
    );
}

/**
 * @param  mixed                                            $callback
 * @return PHPUnit_Framework_MockObject_Stub_ReturnCallback
 * @since  Method available since Release 3.3.0
 */
function returnCallback($callback)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\TestCase::returnCallback',
        func_get_args()
    );
}

/**
 * Returns the current object.
 *
 * This method is useful when mocking a fluent interface.
 *
 * @return PHPUnit_Framework_MockObject_Stub_ReturnSelf
 * @since  Method available since Release 3.6.0
 */
function returnSelf()
{
    return call_user_func_array(
        'PhpUnit\\Framework\\TestCase::returnSelf',
        func_get_args()
    );
}

/**
 * @param  mixed                                    $value
 * @return PHPUnit_Framework_MockObject_Stub_Return
 * @since  Method available since Release 3.0.0
 */
function returnValue($value)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\TestCase::returnValue',
        func_get_args()
    );
}

/**
 * @param  array                                            $valueMap
 * @return PHPUnit_Framework_MockObject_Stub_ReturnValueMap
 * @since  Method available since Release 3.6.0
 */
function returnValueMap(array $valueMap)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\TestCase::returnValueMap',
        func_get_args()
    );
}

/**
 * Returns a PhpUnit\Framework\Constraint\StringContains matcher object.
 *
 * @param  string                                      $string
 * @param  bool                                        $case
 * @return \PhpUnit\Framework\Constraint\StringContains
 * @since  Method available since Release 3.0.0
 */
function stringContains($string, $case = true)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::stringContains',
        func_get_args()
    );
}

/**
 * Returns a PhpUnit\Framework\Constraint\StringEndsWith matcher object.
 *
 * @param  mixed                                       $suffix
 * @return \PhpUnit\Framework\Constraint\StringEndsWith
 * @since  Method available since Release 3.4.0
 */
function stringEndsWith($suffix)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::stringEndsWith',
        func_get_args()
    );
}

/**
 * Returns a PhpUnit\Framework\Constraint\StringStartsWith matcher object.
 *
 * @param  mixed                                         $prefix
 * @return \PhpUnit\Framework\Constraint\StringStartsWith
 * @since  Method available since Release 3.4.0
 */
function stringStartsWith($prefix)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\Assert::stringStartsWith',
        func_get_args()
    );
}

/**
 * @param  Exception                                   $exception
 * @return PHPUnit_Framework_MockObject_Stub_Exception
 * @since  Method available since Release 3.1.0
 */
function throwException(Exception $exception)
{
    return call_user_func_array(
        'PhpUnit\\Framework\\TestCase::throwException',
        func_get_args()
    );
}
