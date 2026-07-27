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

namespace MODX\Revolution\Tests\Controllers\Security;

use MODX\Revolution\modUser;
use MODX\Revolution\MODxControllerTestCase;
use MODX\Revolution\Registry\modDbRegister;
use MODX\Revolution\Registry\modRegistry;
use ReflectionMethod;

/**
 * Tests for passwordless magic-link confirmation (issue #16743).
 *
 * @package modx-test
 * @subpackage modx
 * @group Controllers
 * @group Security
 * @group SecurityLoginController
 */
class SecurityLoginControllerTest extends MODxControllerTestCase
{
    /** @var \SecurityLoginManagerController $controller */
    public $controller;

    public $controllerName = 'SecurityLoginManagerController';
    public $controllerPath = 'security/login';

    /** @var string */
    private $testHash;

    /** @var modUser|null */
    private $testUser;

    /**
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();

        $this->testHash = md5(uniqid('magiclink-test-', true));
        $testUsername = $this->modx->getOption('modx.test.user.username', null, 'unittestuser');
        $this->testUser = $this->modx->getObject(modUser::class, ['username' => $testUsername]);
        if (!$this->testUser) {
            $this->markTestSkipped('Unit test user is not available.');
        }

        /** @var modRegistry $registry */
        $registry = $this->modx->getService('registry', modRegistry::class);
        /** @var modDbRegister $register */
        $register = $registry->getRegister('user', modDbRegister::class);
        $register->connect();
        $register->subscribe('/pwd/magiclink/');
        $register->send('/pwd/magiclink/', [
            $this->testHash => $this->testUser->get('username'),
        ], [
            'ttl' => 300,
        ]);

        unset($_GET['magiclink'], $_GET['magiclink_pending']);
    }

    /**
     * @after
     */
    public function tearDownFixtures()
    {
        unset($_GET['magiclink'], $_GET['magiclink_pending']);
        if (!empty($this->testHash)) {
            $this->readMagicLinkRegistry(true);
        }
        parent::tearDownFixtures();
    }

    public function testMagicLinkPendingGetDoesNotConsumeToken()
    {
        $_GET['magiclink_pending'] = $this->testHash;
        $this->controller->handleMagicLoginLink();

        $this->assertSame($this->testHash, $this->controller->getPlaceholder('magiclink_pending_hash'));
        $this->assertNotEmpty($this->readMagicLinkRegistry(false), 'Pending GET must leave the registry token intact.');
    }

    public function testLegacyMagicLinkGetDoesNotConsumeToken()
    {
        $_GET['magiclink'] = $this->testHash;
        $this->controller->handleMagicLoginLink();

        $this->assertSame($this->testHash, $this->controller->getPlaceholder('magiclink_pending_hash'));
        $this->assertNotEmpty(
            $this->readMagicLinkRegistry(false),
            'Legacy magiclink GET must show confirmation without consuming the token.'
        );
    }

    public function testBuildMagicLinkPendingUrlUsesPendingParameter()
    {
        $method = new ReflectionMethod($this->controller, 'buildMagicLinkPendingUrl');
        $method->setAccessible(true);
        $url = $method->invoke($this->controller, $this->testHash);

        $this->assertStringContainsString('magiclink_pending=' . $this->testHash, $url);
        $this->assertStringNotContainsString('?magiclink=' . $this->testHash, $url);
    }

    public function testInvalidConfirmKeepsPendingPlaceholder()
    {
        $this->controller->setProperties([
            'confirm_magiclink' => '1',
            'magiclink' => 'invalid-hash-for-confirm-test',
            'login_context' => 'mgr',
        ]);
        $this->controller->handlePost();

        $this->assertSame(
            'invalid-hash-for-confirm-test',
            $this->controller->getPlaceholder('magiclink_pending_hash')
        );
        $this->assertNotEmpty(
            $this->readMagicLinkRegistry(false),
            'Failed confirm must not remove a different valid token.'
        );
    }

    /**
     * @param bool $remove
     * @return array
     */
    private function readMagicLinkRegistry($remove = false)
    {
        /** @var modRegistry $registry */
        $registry = $this->modx->getService('registry', modRegistry::class);
        /** @var modDbRegister $register */
        $register = $registry->getRegister('user', modDbRegister::class);
        $register->connect();
        $register->subscribe('/pwd/magiclink/' . $this->testHash);

        return $register->read([
            'poll_limit' => 1,
            'remove_read' => $remove,
        ]);
    }
}
