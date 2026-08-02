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

use MODX\Revolution\Error\modUncaughtErrorHandler;
use MODX\Revolution\modX;
use MODX\Revolution\MODxTestCase;

/**
 * Tests related to the modUncaughtErrorHandler class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Error
 * @group modUncaughtErrorHandler
 */
class modUncaughtErrorHandlerTest extends MODxTestCase
{
    /**
     * @after
     */
    public function tearDownHandlerState()
    {
        modUncaughtErrorHandler::resetForTesting();
        restore_exception_handler();
        restore_error_handler();
    }

    /**
     * @param int  $type
     * @param bool $expected
     * @dataProvider providerIsFatalErrorType
     */
    public function testIsFatalErrorType($type, $expected)
    {
        $this->assertSame($expected, modUncaughtErrorHandler::isFatalErrorType($type));
    }

    /**
     * @return array
     */
    public function providerIsFatalErrorType()
    {
        return [
            [E_ERROR, true],
            [E_PARSE, true],
            [E_CORE_ERROR, true],
            [E_COMPILE_ERROR, true],
            [E_WARNING, false],
            [E_NOTICE, false],
            [E_USER_ERROR, false],
            [E_USER_WARNING, false],
        ];
    }

    public function testFormatPhpError()
    {
        $formatted = modUncaughtErrorHandler::formatPhpError([
            'message' => 'Division by zero',
            'file' => '/tmp/example.php',
            'line' => 12,
        ]);

        $this->assertSame(
            'Fatal error: Division by zero in /tmp/example.php on line 12',
            $formatted
        );
    }

    public function testIsEnabledDefaultsToTrue()
    {
        $this->assertTrue(modUncaughtErrorHandler::isEnabled($this->modx));
    }

    public function testHandleExceptionLogsFatal()
    {
        $modx = $this->getMockBuilder(modX::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['log'])
            ->getMock();
        $modx->expects($this->once())
            ->method('log')
            ->with(
                modX::LOG_LEVEL_FATAL,
                $this->stringContains('TypeError: Invalid argument type'),
                '',
                '',
                $this->anything(),
                $this->anything()
            );

        $handler = new modUncaughtErrorHandler($modx);
        $handler->handleException(new \TypeError('Invalid argument type'));
    }

    public function testHandleShutdownIgnoresNonFatalErrors()
    {
        $modx = $this->getMockBuilder(modX::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['log'])
            ->getMock();
        $modx->expects($this->never())->method('log');

        $handler = new modUncaughtErrorHandler($modx);

        $original = set_error_handler(function () {
            return true;
        });
        trigger_error('notice level', E_USER_NOTICE);
        restore_error_handler();

        $handler->handleShutdown();
    }

    public function testRegisterInstallsExceptionHandler()
    {
        if (defined('XPDO_CLI_MODE') && XPDO_CLI_MODE) {
            $this->markTestSkipped('HTTP fatal handling is disabled in CLI mode.');
        }

        modUncaughtErrorHandler::register($this->modx);
        $previous = set_exception_handler(null);
        $this->assertIsArray($previous);
        $this->assertSame('handleException', $previous[1]);
        restore_exception_handler();
    }
}
