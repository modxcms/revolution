<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package modx-test
 */

namespace MODX\Revolution\Tests\Model;

use Monolog\Logger;
use MODX\Revolution\Logging\MonologLoggerFactory;
use MODX\Revolution\MODxTestCase;
use Psr\Log\LoggerInterface;
use xPDO\xPDO;
use xPDO\Cache\xPDOCacheManager;

/**
 * Tests for the default Monolog factory and log_monolog opt-in.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group modX
 * @group Logging
 */
class MonologLoggerFactoryTest extends MODxTestCase
{
    /**
     * @var mixed Original log_monolog option value.
     */
    private $originalLogMonolog;

    /**
     * @var int Original log level.
     */
    private $originalLogLevel;

    /**
     * @var mixed Original log target.
     */
    private $originalLogTarget;

    /**
     * @before
     * @throws \xPDO\xPDOException
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->originalLogMonolog = $this->modx->getOption('log_monolog');
        $this->originalLogLevel = $this->modx->getLogLevel();
        $this->originalLogTarget = $this->modx->getLogTarget();
        $this->modx->logger = null;
        $this->unsetContainerLogger();
    }

    /**
     * @after
     */
    public function tearDownFixtures()
    {
        $this->modx->setOption('log_monolog', $this->originalLogMonolog);
        $this->modx->setLogLevel($this->originalLogLevel);
        $this->modx->setLogTarget($this->originalLogTarget);
        $this->modx->logger = null;
        $this->unsetContainerLogger();
        parent::tearDownFixtures();
    }

    public function testCreateReturnsMonologLogger()
    {
        $logger = MonologLoggerFactory::create($this->modx);

        $this->assertInstanceOf(LoggerInterface::class, $logger);
        $this->assertInstanceOf(Logger::class, $logger);
    }

    public function testApplyDoesNothingWhenDisabled()
    {
        $this->modx->setOption('log_monolog', false);
        $this->modx->logger = null;
        $this->unsetContainerLogger();

        MonologLoggerFactory::apply($this->modx);

        $this->assertFalse($this->modx->services->has(LoggerInterface::class));
        $this->assertNull($this->modx->logger);
    }

    public function testApplySetsLoggerAndContainerWhenEnabled()
    {
        $this->modx->setOption('log_monolog', true);

        MonologLoggerFactory::apply($this->modx);

        $this->assertTrue($this->modx->services->has(LoggerInterface::class));
        $this->assertInstanceOf(LoggerInterface::class, $this->modx->logger);
        $this->assertSame(
            $this->modx->services->get(LoggerInterface::class),
            $this->modx->logger
        );
    }

    public function testOptInWritesToResolvedErrorLog()
    {
        $cachePath = $this->modx->getCachePath();
        $filepath = $cachePath . xPDOCacheManager::LOG_DIR;
        $filename = 'monolog_factory_test.log';
        $fullpath = $filepath . $filename;
        if (file_exists($fullpath)) {
            unlink($fullpath);
        }

        $this->modx->setLogTarget([
            'target' => 'FILE',
            'options' => [
                'filename' => $filename,
                'filepath' => $filepath,
            ],
        ]);
        $this->modx->setOption('log_monolog', true);
        $this->modx->setLogLevel(xPDO::LOG_LEVEL_DEBUG);

        $this->unsetContainerLogger();
        MonologLoggerFactory::apply($this->modx);

        $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Monolog opt-in test', '', 'UnitTest', __FILE__, 42);

        $this->assertFileExists($fullpath);
        $contents = file_get_contents($fullpath);
        $this->assertStringContainsString('Monolog opt-in test', $contents);
        unlink($fullpath);
    }

    public function testCreateHonorsCustomLogTargetPath()
    {
        $this->modx->setLogTarget([
            'target' => 'FILE',
            'options' => [
                'filename' => 'custom.log',
                'filepath' => '/tmp/modx-logs/',
            ],
        ]);

        $logger = MonologLoggerFactory::create($this->modx);
        $this->assertInstanceOf(Logger::class, $logger);

        $handlers = $logger->getHandlers();
        $this->assertNotEmpty($handlers);
        $this->assertStringContainsString('custom.log', (string) $handlers[0]->getUrl());
    }

    private function unsetContainerLogger(): void
    {
        if ($this->modx->services->has(LoggerInterface::class)) {
            $this->modx->services->offsetUnset(LoggerInterface::class);
        }
    }
}
