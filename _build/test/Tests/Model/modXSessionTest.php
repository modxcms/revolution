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

use MODX\Revolution\modUser;
use MODX\Revolution\modX;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\MODxTestHarness;

/**
 * Tests related to session handling in the main modX class.
 *
 * Each test runs in its own process with XPDO_CLI_MODE disabled, so that real PHP
 * sessions can be started (tests prefixed with testCli keep CLI mode enabled).
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group modX
 * @group Session
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class modXSessionTest extends MODxTestCase
{
    public const SESSION_NAME = 'modxtest';

    /**
     * @before
     */
    public function setUpFixtures()
    {
        if (strpos($this->getName(false), 'testCli') !== 0 && !defined('XPDO_CLI_MODE')) {
            define('XPDO_CLI_MODE', false);
        }
    }

    /**
     * @after
     */
    public function tearDownFixtures()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    /**
     * Get a modX instance initialized with specific session options.
     *
     * @param array $options Options to override Settings when initializing modX.
     * @return modX
     */
    protected function getModx(array $options = [])
    {
        $options = array_merge([
            'session_name' => self::SESSION_NAME,
            'session_handler_class' => '',
            'session_save_path' => sys_get_temp_dir(),
        ], $options);
        $this->modx = MODxTestHarness::getFixture(modX::class, 'modx-session', true, ['initialize' => $options]);

        return $this->modx;
    }

    public function testAnonymousSessionsStartASessionWhenInitializing()
    {
        $modx = $this->getModx(['anonymous_sessions' => true]);

        $this->assertSame(PHP_SESSION_ACTIVE, session_status());
        $this->assertSame(modX::SESSION_STATE_INITIALIZED, $modx->getSessionState());
        $this->assertSame(self::SESSION_NAME, session_name());
    }

    public function testNoSessionIsStartedWithoutAnonymousSessions()
    {
        $modx = $this->getModx(['anonymous_sessions' => false]);

        $this->assertSame(PHP_SESSION_NONE, session_status());
        $this->assertSame(modX::SESSION_STATE_UNINITIALIZED, $modx->getSessionState());
        $this->assertSame(
            self::SESSION_NAME,
            session_name(),
            'The session should be configured even if it is not started'
        );
    }

    public function testSessionCookieStartsASessionWithoutAnonymousSessions()
    {
        $_COOKIE[self::SESSION_NAME] = 'modxsessiontest0123456789';
        $modx = $this->getModx(['anonymous_sessions' => false]);

        $this->assertSame(PHP_SESSION_ACTIVE, session_status());
        $this->assertSame(modX::SESSION_STATE_INITIALIZED, $modx->getSessionState());
    }

    public function testStartSessionOnDemand()
    {
        $modx = $this->getModx(['anonymous_sessions' => false]);

        $this->assertTrue($modx->startSession());
        $this->assertSame(PHP_SESSION_ACTIVE, session_status());
        $this->assertSame(modX::SESSION_STATE_INITIALIZED, $modx->getSessionState());
        $this->assertTrue($modx->startSession(), 'Starting an already started session should succeed');
    }

    /**
     * Writing to $_SESSION before a session is active must not prevent a session from starting.
     */
    public function testStartSessionOnDemandAfterSessionGlobalIsWritten()
    {
        $modx = $this->getModx(['anonymous_sessions' => false]);

        $_SESSION['manager_language'] = 'de';
        $modx->hasPermission('load');

        $this->assertSame(modX::SESSION_STATE_UNINITIALIZED, $modx->getSessionState());
        $this->assertTrue($modx->startSession());
        $this->assertSame(PHP_SESSION_ACTIVE, session_status());
        $this->assertSame(modX::SESSION_STATE_INITIALIZED, $modx->getSessionState());
        $this->assertSame('de', $_SESSION['manager_language']);
    }

    public function testAddSessionContextStartsASession()
    {
        $modx = $this->getModx(['anonymous_sessions' => false]);

        $_SESSION['modx.user.contextTokens'] = [];
        $modx->hasPermission('load');

        /** @var modUser $user */
        $user = $modx->newObject(modUser::class);
        $user->set('id', 123456789);
        $user->addSessionContext('web');

        $this->assertSame(PHP_SESSION_ACTIVE, session_status());
        $this->assertSame(['web' => 123456789], $_SESSION['modx.user.contextTokens']);
        $this->assertNotEmpty($_SESSION['modx.web.user.token']);
        $this->assertTrue($user->isAuthenticated('web'));
    }

    public function testStartSessionAfterSessionIsDestroyed()
    {
        $modx = $this->getModx(['anonymous_sessions' => true]);
        session_destroy();

        $this->assertSame(PHP_SESSION_NONE, session_status());
        $this->assertTrue($modx->startSession());
        $this->assertSame(PHP_SESSION_ACTIVE, session_status());
    }

    public function testSessionStateRemainsInitializedAfterSessionIsClosed()
    {
        $modx = $this->getModx(['anonymous_sessions' => true]);
        session_write_close();

        $this->assertSame(modX::SESSION_STATE_INITIALIZED, $modx->getSessionState());
    }

    public function testExternalSessionIsDetected()
    {
        session_save_path(sys_get_temp_dir());
        session_start();
        $modx = $this->getModx(['anonymous_sessions' => true]);

        $this->assertSame(modX::SESSION_STATE_EXTERNAL, $modx->getSessionState());
        $this->assertNotSame(self::SESSION_NAME, session_name(), 'An external session should not be reconfigured');
        $this->assertTrue($modx->startSession());
        $this->assertSame(modX::SESSION_STATE_EXTERNAL, $modx->getSessionState());
    }

    public function testSessionIsNotConfiguredWhenSessionsAreDisabled()
    {
        $modx = $this->getModx(['session_enabled' => false, 'anonymous_sessions' => true]);

        $this->assertSame(PHP_SESSION_NONE, session_status());
        $this->assertNotSame(self::SESSION_NAME, session_name());
        $this->assertSame(modX::SESSION_STATE_UNINITIALIZED, $modx->getSessionState());
    }

    public function testCliSessionIsUnavailable()
    {
        $modx = $this->getModx(['anonymous_sessions' => true]);

        $this->assertSame(PHP_SESSION_NONE, session_status());
        $this->assertSame(modX::SESSION_STATE_UNAVAILABLE, $modx->getSessionState());
        $this->assertFalse($modx->startSession());

        $_SESSION = [];
        $this->assertTrue($modx->startSession(), 'CLI scripts may provide their own $_SESSION');
        $this->assertSame(PHP_SESSION_NONE, session_status());
        $this->assertSame(modX::SESSION_STATE_UNAVAILABLE, $modx->getSessionState());
    }
}
