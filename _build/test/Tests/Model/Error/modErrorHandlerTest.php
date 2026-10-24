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
namespace MODX\Revolution\Tests\Model\Error;

use MODX\Revolution\Error\modErrorHandler;
use MODX\Revolution\Error\modUncaughtErrorHandler;
use MODX\Revolution\modX;
use MODX\Revolution\MODxTestCase;
use RuntimeException;

/**
 * Tests related to the modErrorHandler class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Error
 * @group modErrorHandler
 */
class modErrorHandlerTest extends MODxTestCase
{
    /** @var modErrorHandler */
    public $handler;

    /**
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->handler = new modErrorHandler($this->modx);
    }

    /**
     * @after
     */
    public function tearDownHandlerState()
    {
        modUncaughtErrorHandler::resetForTesting();
        restore_error_handler();
    }

    public function testHandleErrorUserErrorTriggersFatalResponseWhenEnabled()
    {
        if (defined('XPDO_CLI_MODE') && XPDO_CLI_MODE) {
            $this->markTestSkipped('HTTP fatal handling is disabled in CLI mode.');
        }

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Fatal log invoked');

        $modx = $this->getMockBuilder(modX::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getOption', 'log'])
            ->getMock();
        $modx->method('getOption')->willReturnMap([
            ['send_http_500_on_error', null, true, true],
        ]);
        $modx->expects($this->once())
            ->method('log')
            ->with(
                modX::LOG_LEVEL_FATAL,
                $this->stringContains('User error: simulated user error'),
                '',
                '',
                '/tmp/test.php',
                10
            )
            ->willThrowException(new RuntimeException('Fatal log invoked'));

        $handler = new modErrorHandler($modx);
        $handler->handleError(E_USER_ERROR, 'simulated user error', '/tmp/test.php', 10);
    }
}
