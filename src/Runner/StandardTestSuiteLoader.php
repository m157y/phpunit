<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpUnit\Runner;

use PhpUnit\Framework\Exception;
use PhpUnit\Util\Fileloader;
use PhpUnit\Util\Filesystem;
use ReflectionClass;

/**
 * The standard test suite loader.
 *
 * @since Class available since Release 2.0.0
 */
class StandardTestSuiteLoader implements TestSuiteLoader
{
    /**
     * @param  string                      $suiteClassName
     * @param  string                      $suiteClassFile
     * @return \ReflectionClass
     * @throws \PhpUnit\Framework\Exception
     */
    public function load($suiteClassName, $suiteClassFile = '')
    {
        $suiteClassName = str_replace('.php', '', $suiteClassName);

        if (empty($suiteClassFile)) {
            $suiteClassFile = Filesystem::classNameToFilename(
                $suiteClassName
            );
        }

        if (!class_exists($suiteClassName, false)) {
            $loadedClasses = get_declared_classes();

            $filename = Fileloader::checkAndLoad($suiteClassFile);

            $loadedClasses = array_values(
                array_diff(get_declared_classes(), $loadedClasses)
            );
        }

        if (!class_exists($suiteClassName, false) && !empty($loadedClasses)) {
            $offset = 0 - strlen($suiteClassName);

            foreach ($loadedClasses as $loadedClass) {
                $class = new ReflectionClass($loadedClass);
                if (substr($loadedClass, $offset) === $suiteClassName &&
                    $class->getFileName() == $filename) {
                    $suiteClassName = $loadedClass;
                    break;
                }
            }
        }

        if (!class_exists($suiteClassName, false) && !empty($loadedClasses)) {
            $testCaseClass = 'PhpUnit\\Framework\\TestCase';

            foreach ($loadedClasses as $loadedClass) {
                $class     = new ReflectionClass($loadedClass);
                $classFile = $class->getFileName();

                if ($class->isSubclassOf($testCaseClass) &&
                    !$class->isAbstract()) {
                    $suiteClassName = $loadedClass;
                    $testCaseClass  = $loadedClass;

                    if ($classFile == realpath($suiteClassFile)) {
                        break;
                    }
                }

                if ($class->hasMethod('suite')) {
                    $method = $class->getMethod('suite');

                    if (!$method->isAbstract() &&
                        $method->isPublic() &&
                        $method->isStatic()) {
                        $suiteClassName = $loadedClass;

                        if ($classFile == realpath($suiteClassFile)) {
                            break;
                        }
                    }
                }
            }
        }

        if (class_exists($suiteClassName, false)) {
            $class = new ReflectionClass($suiteClassName);

            if ($class->getFileName() == realpath($suiteClassFile)) {
                return $class;
            }
        }

        throw new Exception(
            sprintf(
                "Class '%s' could not be found in '%s'.",
                $suiteClassName,
                $suiteClassFile
            )
        );
    }

    /**
     * @param  ReflectionClass $aClass
     * @return ReflectionClass
     */
    public function reload(ReflectionClass $aClass)
    {
        return $aClass;
    }
}
