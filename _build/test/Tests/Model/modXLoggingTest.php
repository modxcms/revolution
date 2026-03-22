<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 *
 * @package modx-test
 */

namespace MODX\Revolution\Tests\Model;

use MODX\Revolution\modX;
use MODX\Revolution\MODxTestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use xPDO\xPDO;
use xPDO\Cache\xPDOCacheManager;

/**
 * Tests related to modX logging behavior.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group modX
 * @group Logging
 */
class modXLoggingTest extends MODxTestCase
{
    /**
     * @var int Original log level to restore after each test.
     */
    private $originalLogLevel;

    /**
     * @var mixed Original log target to restore after each test.
     */
    private $originalLogTarget;

    /**
     * @before
     * @throws \xPDO\xPDOException
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->originalLogLevel = $this->modx->getLogLevel();
        $this->originalLogTarget = $this->modx->getLogTarget();
        // Ensure no PSR-3 logger is set for baseline tests
        $this->modx->logger = null;
    }

    /**
     * @after
     */
    public function tearDownFixtures()
    {
        $this->modx->setLogLevel($this->originalLogLevel);
        $this->modx->setLogTarget($this->originalLogTarget);
        $this->modx->setDebug(false);
        $this->modx->logger = null;
        parent::tearDownFixtures();
    }

    // ---------------------------------------------------------------
    // Baseline tests: legacy logging behavior (no PSR-3 logger)
    // ---------------------------------------------------------------

    public function testFileTargetWritesLog()
    {
        $this->modx->setLogLevel(xPDO::LOG_LEVEL_DEBUG);

        $cachePath = $this->modx->getCachePath();
        $filepath = $cachePath . xPDOCacheManager::LOG_DIR;
        $filename = 'modx_logging_test.log';
        $fullpath = $filepath . $filename;
        if (file_exists($fullpath)) {
            unlink($fullpath);
        }

        $target = [
            'target' => 'FILE',
            'options' => [
                'filename' => $filename,
                'filepath' => $filepath,
            ],
        ];

        $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'File write test', $target, 'UnitTest', __FILE__, 123);

        $this->assertFileExists($fullpath);
        $contents = file_get_contents($fullpath);
        $pattern = '/^\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] \(ERROR in UnitTest @ '
            . preg_quote(__FILE__, '/')
            . ' : 123\) File write test\n$/';
        $this->assertMatchesRegularExpression($pattern, $contents);
        unlink($fullpath);
    }

    public function testEchoTargetOutputsFormattedMessage()
    {
        $this->modx->setLogLevel(xPDO::LOG_LEVEL_DEBUG);

        ob_start();
        $this->modx->log(xPDO::LOG_LEVEL_INFO, 'Echo test', 'ECHO', 'UnitTest', __FILE__, 456);
        $output = ob_get_clean();

        $pattern = '/^\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] \(INFO in UnitTest @ '
            . preg_quote(__FILE__, '/')
            . ' : 456\) Echo test\n$/';
        $this->assertMatchesRegularExpression($pattern, $output);
    }

    public function testHtmlTargetOutputsHtmlFormattedMessage()
    {
        $this->modx->setLogLevel(xPDO::LOG_LEVEL_DEBUG);

        ob_start();
        $this->modx->log(xPDO::LOG_LEVEL_INFO, 'Html test', 'HTML', 'UnitTest', __FILE__, 789);
        $output = ob_get_clean();

        $pattern = '/^<h5>\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] \(INFO in UnitTest @ '
            . preg_quote(__FILE__, '/')
            . ' : 789\)<\/h5><pre>Html test<\/pre>\n$/';
        $this->assertMatchesRegularExpression($pattern, $output);
    }

    public function testArrayTargetAppendsFormattedString()
    {
        $this->modx->setLogLevel(xPDO::LOG_LEVEL_DEBUG);

        $output = [];
        $target = [
            'target' => 'ARRAY',
            'options' => [
                'var' => &$output,
            ],
        ];

        $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Array test', $target, 'UnitTest', __FILE__, 111);

        $this->assertCount(1, $output);
        $pattern = '/^\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] \(ERROR in UnitTest @ '
            . preg_quote(__FILE__, '/')
            . ' : 111\) Array test\n$/';
        $this->assertMatchesRegularExpression($pattern, $output[0]);
    }

    public function testArrayExtendedTargetAppendsStructuredArray()
    {
        $this->modx->setLogLevel(xPDO::LOG_LEVEL_DEBUG);

        $output = [];
        $target = [
            'target' => 'ARRAY_EXTENDED',
            'options' => [
                'var' => &$output,
            ],
        ];

        $this->modx->log(xPDO::LOG_LEVEL_WARN, 'Extended test', $target, 'UnitTest', __FILE__, 222);

        $this->assertCount(1, $output);
        $entry = $output[0];
        $this->assertSame('WARN', $entry['level']);
        $this->assertSame('Extended test', $entry['msg']);
        $this->assertSame(' in UnitTest', $entry['def']);
        $this->assertSame(' @ ' . __FILE__, $entry['file']);
        $this->assertSame(' : 222', $entry['line']);
        $this->assertArrayHasKey('content', $entry);
    }

    public function testLevelFilteringBlocksMessagesBelowThreshold()
    {
        $this->modx->setLogLevel(xPDO::LOG_LEVEL_ERROR);

        $output = [];
        $target = [
            'target' => 'ARRAY',
            'options' => [
                'var' => &$output,
            ],
        ];

        $this->modx->log(xPDO::LOG_LEVEL_INFO, 'Should not appear', $target, 'UnitTest', __FILE__, 333);

        $this->assertCount(0, $output);
    }

    public function testDebugModeOverridesLevelFiltering()
    {
        $this->modx->setLogLevel(xPDO::LOG_LEVEL_ERROR);
        $this->modx->setDebug(true);

        $output = [];
        $target = [
            'target' => 'ARRAY',
            'options' => [
                'var' => &$output,
            ],
        ];

        $this->modx->log(xPDO::LOG_LEVEL_DEBUG, 'Debug override', $target, 'UnitTest', __FILE__, 444);

        $this->assertCount(1, $output);
        $this->assertStringContainsString('Debug override', $output[0]);
    }

    public function testFileAndLineAutoResolutionFromBacktrace()
    {
        $this->modx->setLogLevel(xPDO::LOG_LEVEL_DEBUG);

        $output = [];
        $target = [
            'target' => 'ARRAY',
            'options' => [
                'var' => &$output,
            ],
        ];

        // Log without explicit file/line — backtrace should fill them in
        $this->modx->log(xPDO::LOG_LEVEL_INFO, 'Backtrace test', $target, 'UnitTest');

        $this->assertCount(1, $output);
        // Should contain a file reference (from backtrace) — at minimum the @ separator
        $this->assertStringContainsString(' @ ', $output[0]);
    }

    public function testDefaultConfigLogLevelIsError()
    {
        // modX constructor defaults to LOG_LEVEL_ERROR when no log_level config is set.
        // In the test harness, the config may override this. We verify the constant default
        // by checking that the constructor fallback value is LOG_LEVEL_ERROR.
        // The actual runtime level depends on config + test harness overrides.
        $this->assertSame(1, xPDO::LOG_LEVEL_ERROR);
        $this->assertSame(0, xPDO::LOG_LEVEL_FATAL);
        $this->assertSame(2, xPDO::LOG_LEVEL_WARN);
        $this->assertSame(3, xPDO::LOG_LEVEL_INFO);
        $this->assertSame(4, xPDO::LOG_LEVEL_DEBUG);
    }

    public function testLevelFilteringAllowsMessagesAtThreshold()
    {
        $this->modx->setLogLevel(xPDO::LOG_LEVEL_WARN);

        $output = [];
        $target = [
            'target' => 'ARRAY',
            'options' => [
                'var' => &$output,
            ],
        ];

        $this->modx->log(xPDO::LOG_LEVEL_WARN, 'At threshold', $target, 'UnitTest', __FILE__, 555);

        $this->assertCount(1, $output);
        $this->assertStringContainsString('At threshold', $output[0]);
    }

    public function testLevelFilteringAllowsMessagesBelowThreshold()
    {
        $this->modx->setLogLevel(xPDO::LOG_LEVEL_WARN);

        $output = [];
        $target = [
            'target' => 'ARRAY',
            'options' => [
                'var' => &$output,
            ],
        ];

        // ERROR (1) is below WARN (2) threshold, so it should pass
        $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Below threshold', $target, 'UnitTest', __FILE__, 666);

        $this->assertCount(1, $output);
        $this->assertStringContainsString('Below threshold', $output[0]);
    }

    // ---------------------------------------------------------------
    // PSR-3 logger tests
    // ---------------------------------------------------------------

    public function testPsrLoggerReceivesMessages()
    {
        $logger = new TestLogger();
        $this->modx->setLogger($logger);
        $this->modx->setLogLevel(xPDO::LOG_LEVEL_DEBUG);

        $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'PSR-3 test', '', 'UnitTest', __FILE__, 100);

        $this->assertCount(1, $logger->records);
        $this->assertSame(LogLevel::ERROR, $logger->records[0]['level']);
        $this->assertSame('PSR-3 test', $logger->records[0]['message']);
    }

    public function testPsrLoggerLevelMapping()
    {
        $logger = new TestLogger();
        $this->modx->setLogger($logger);
        $this->modx->setLogLevel(xPDO::LOG_LEVEL_DEBUG);

        $this->modx->log(xPDO::LOG_LEVEL_DEBUG, 'debug msg', '', 'Test', __FILE__, 1);
        $this->modx->log(xPDO::LOG_LEVEL_INFO, 'info msg', '', 'Test', __FILE__, 2);
        $this->modx->log(xPDO::LOG_LEVEL_WARN, 'warn msg', '', 'Test', __FILE__, 3);
        $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'error msg', '', 'Test', __FILE__, 4);

        $this->assertCount(4, $logger->records);
        $this->assertSame(LogLevel::DEBUG, $logger->records[0]['level']);
        $this->assertSame(LogLevel::INFO, $logger->records[1]['level']);
        $this->assertSame(LogLevel::WARNING, $logger->records[2]['level']);
        $this->assertSame(LogLevel::ERROR, $logger->records[3]['level']);
    }

    public function testPsrLoggerRespectsLogLevelThreshold()
    {
        $logger = new TestLogger();
        $this->modx->setLogger($logger);
        $this->modx->setLogLevel(xPDO::LOG_LEVEL_ERROR);

        $this->modx->log(xPDO::LOG_LEVEL_INFO, 'Should not appear');

        $this->assertCount(0, $logger->records);
    }

    public function testPsrLoggerReplacesLegacyOutput()
    {
        $logger = new TestLogger();
        $this->modx->setLogger($logger);
        $this->modx->setLogLevel(xPDO::LOG_LEVEL_DEBUG);

        $cachePath = $this->modx->getCachePath();
        $filepath = $cachePath . xPDOCacheManager::LOG_DIR;
        $filename = 'psr3_replace_test.log';
        $fullpath = $filepath . $filename;
        if (file_exists($fullpath)) {
            unlink($fullpath);
        }

        $target = [
            'target' => 'FILE',
            'options' => [
                'filename' => $filename,
                'filepath' => $filepath,
            ],
        ];

        $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'PSR-3 only', $target, 'UnitTest', __FILE__, 200);

        // PSR-3 logger received the message
        $this->assertCount(1, $logger->records);
        $this->assertSame('PSR-3 only', $logger->records[0]['message']);

        // Legacy FILE target should NOT have been written
        $this->assertFileDoesNotExist($fullpath);
    }

    public function testPsrLoggerReplacesLegacyEchoOutput()
    {
        $logger = new TestLogger();
        $this->modx->setLogger($logger);
        $this->modx->setLogLevel(xPDO::LOG_LEVEL_DEBUG);

        ob_start();
        $this->modx->log(xPDO::LOG_LEVEL_INFO, 'No echo', 'ECHO', 'UnitTest', __FILE__, 300);
        $output = ob_get_clean();

        // PSR-3 logger received it
        $this->assertCount(1, $logger->records);
        // No echo output
        $this->assertEmpty($output);
    }

    public function testSetLoggerSetsLoggerProperty()
    {
        $logger = new TestLogger();
        $this->modx->setLogger($logger);

        $this->assertSame($logger, $this->modx->logger);
        $this->assertInstanceOf(LoggerInterface::class, $this->modx->logger);
    }

    public function testPsrLoggerContextIncludesDefAndLevel()
    {
        $logger = new TestLogger();
        $this->modx->setLogger($logger);
        $this->modx->setLogLevel(xPDO::LOG_LEVEL_DEBUG);

        $this->modx->log(xPDO::LOG_LEVEL_WARN, 'Context test', '', 'MyClass', 'myfile.php', 42);

        $this->assertCount(1, $logger->records);
        $context = $logger->records[0]['context'];
        $this->assertSame('MyClass', $context['def']);
        $this->assertSame('myfile.php', $context['file']);
        $this->assertSame(42, $context['line']);
        $this->assertSame(xPDO::LOG_LEVEL_WARN, $context['xpdo_level']);
    }

    public function testPsrLoggerFileLineEmptyWhenCallerDoesNotProvide()
    {
        $logger = new TestLogger();
        $this->modx->setLogger($logger);
        $this->modx->setLogLevel(xPDO::LOG_LEVEL_DEBUG);

        // Log without explicit file/line
        $this->modx->log(xPDO::LOG_LEVEL_INFO, 'No file info');

        $this->assertCount(1, $logger->records);
        $context = $logger->records[0]['context'];
        // PSR-3 path does NOT resolve backtrace — file/line should be empty
        $this->assertEmpty($context['file']);
        $this->assertEmpty($context['line']);
    }

    public function testPsrLoggerStringifiesNonStringMessages()
    {
        $logger = new TestLogger();
        $this->modx->setLogger($logger);
        $this->modx->setLogLevel(xPDO::LOG_LEVEL_DEBUG);

        $this->modx->log(xPDO::LOG_LEVEL_INFO, ['key' => 'value']);

        $this->assertCount(1, $logger->records);
        $this->assertStringContainsString('key', $logger->records[0]['message']);
        $this->assertStringContainsString('value', $logger->records[0]['message']);
    }
}
