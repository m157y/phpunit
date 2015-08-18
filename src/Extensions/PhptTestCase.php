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

use Exception as PhpException;
use PHP_Timer;
use PhpUnit\Framework\Assert;
use PhpUnit\Framework\AssertionFailedError;
use PhpUnit\Framework\Exception;
use PhpUnit\Framework\SelfDescribing;
use PhpUnit\Framework\SkippedTestError;
use PhpUnit\Framework\Test;
use PhpUnit\Framework\TestResult;
use PhpUnit\Util\InvalidArgumentHelper;
use PhpUnit\Util\Php as PhpUtil;
use Throwable;

/**
 * Runner for PHPT test cases.
 *
 * @since Class available since Release 3.1.4
 */
class PhptTestCase implements Test, SelfDescribing
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var array
     */
    private $settings = [
        'allow_url_fopen=1',
        'auto_append_file=',
        'auto_prepend_file=',
        'disable_functions=',
        'display_errors=1',
        'docref_root=',
        'docref_ext=.html',
        'error_append_string=',
        'error_prepend_string=',
        'error_reporting=-1',
        'html_errors=0',
        'log_errors=0',
        'magic_quotes_runtime=0',
        'output_handler=',
        'open_basedir=',
        'output_buffering=Off',
        'report_memleaks=0',
        'report_zend_debug=0',
        'safe_mode=0',
        'track_errors=1',
        'xdebug.default_enable=0'
    ];

    /**
     * Constructs a test case with the given filename.
     *
     * @param  string                      $filename
     * @throws \PhpUnit\Framework\Exception
     */
    public function __construct($filename)
    {
        if (!is_string($filename)) {
            throw InvalidArgumentHelper::factory(1, 'string');
        }

        if (!is_file($filename)) {
            throw new Exception(
                sprintf(
                    'File "%s" does not exist.',
                    $filename
                )
            );
        }

        $this->filename = $filename;
    }

    /**
     * Counts the number of test cases executed by run(TestResult result).
     *
     * @return int
     */
    public function count()
    {
        return 1;
    }

    /**
     * Runs a test and collects its result in a TestResult instance.
     *
     * @param  \PhpUnit\Framework\TestResult $result
     * @return \PhpUnit\Framework\TestResult
     */
    public function run(TestResult $result = null)
    {
        $sections = $this->parse();
        $code     = $this->render($sections['FILE']);

        if ($result === null) {
            $result = new TestResult;
        }

        $php      = PhpUtil::factory();
        $skip     = false;
        $time     = 0;
        $settings = $this->settings;

        $result->startTest($this);

        if (isset($sections['INI'])) {
            $settings = array_merge($settings, $this->parseIniSection($sections['INI']));
        }

        if (isset($sections['SKIPIF'])) {
            $jobResult = $php->runJob($sections['SKIPIF'], $settings);

            if (!strncasecmp('skip', ltrim($jobResult['stdout']), 4)) {
                if (preg_match('/^\s*skip\s*(.+)\s*/i', $jobResult['stdout'], $message)) {
                    $message = substr($message[1], 2);
                } else {
                    $message = '';
                }

                $result->addFailure($this, new SkippedTestError($message), 0);

                $skip = true;
            }
        }

        if (!$skip) {
            PHP_Timer::start();
            $jobResult = $php->runJob($code, $settings);
            $time      = PHP_Timer::stop();

            if (isset($sections['EXPECT'])) {
                $assertion = 'assertEquals';
                $expected  = $sections['EXPECT'];
            } else {
                $assertion = 'assertStringMatchesFormat';
                $expected  = $sections['EXPECTF'];
            }

            $output   = preg_replace('/\r\n/', "\n", trim($jobResult['stdout']));
            $expected = preg_replace('/\r\n/', "\n", trim($expected));

            try {
                Assert::$assertion($expected, $output);
            } catch (AssertionFailedError $e) {
                $result->addFailure($this, $e, $time);
            } catch (Throwable $t) {
                $result->addError($this, $t, $time);
            } catch (PhpException $e) {
                $result->addError($this, $e, $time);
            }
        }

        $result->endTest($this, $time);

        return $result;
    }

    /**
     * Returns the name of the test case.
     *
     * @return string
     */
    public function getName()
    {
        return $this->toString();
    }

    /**
     * Returns a string representation of the test case.
     *
     * @return string
     */
    public function toString()
    {
        return $this->filename;
    }

    /**
     * @return array
     * @throws \PhpUnit\Framework\Exception
     */
    private function parse()
    {
        $sections = [];
        $section  = '';

        foreach (file($this->filename) as $line) {
            if (preg_match('/^--([_A-Z]+)--/', $line, $result)) {
                $section            = $result[1];
                $sections[$section] = '';
                continue;
            } elseif (empty($section)) {
                throw new Exception('Invalid PHPT file');
            }

            $sections[$section] .= $line;
        }

        if (!isset($sections['FILE']) ||
            (!isset($sections['EXPECT']) && !isset($sections['EXPECTF']))) {
            throw new Exception('Invalid PHPT file');
        }

        return $sections;
    }

    /**
     * @param  string $code
     * @return string
     */
    private function render($code)
    {
        return str_replace(
            [
            '__DIR__',
            '__FILE__'
            ],
            [
            "'" . dirname($this->filename) . "'",
            "'" . $this->filename . "'"
            ],
            $code
        );
    }

    /**
     * Parse --INI-- section key value pairs and return as array.
     *
     * @param string
     * @return array
     */
    protected function parseIniSection($content)
    {
        return preg_split('/\n|\r/', $content, -1, PREG_SPLIT_NO_EMPTY);
    }
}
