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

namespace MODX\Revolution\Tests\Processors\Security;

use MODX\Revolution\modUser;
use MODX\Revolution\modUserProfile;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Security\Login;

/**
 * Ensures Login::afterLogin() refreshes $modx->user for non-mgr contexts so
 * OnWebLogin plugins see the authenticated user via $modx->getUser().
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Security
 * @group Login
 */
class LoginTest extends MODxTestCase
{
    public const USERNAME = 'unit-test-login-getuser';
    public const PLUGIN_ID = 99917015;

    /** @var modUser */
    protected $user;

    /**
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->removeFixtures();
        $this->modx->error->reset();

        $user = $this->modx->newObject(modUser::class);
        $user->fromArray([
            'username' => self::USERNAME,
            'active' => true,
        ]);
        $user->set('password', 'unit-test-login-password');

        $profile = $this->modx->newObject(modUserProfile::class);
        $profile->fromArray([
            'email' => 'unit-test-login-getuser@example.com',
            'fullname' => 'Unit Test Login',
            'failedlogincount' => 0,
            'blocked' => 0,
        ]);
        $user->addOne($profile, 'Profile');

        if (!$user->save()) {
            $this->fail('Could not save fixture user for LoginTest');
        }
        $this->user = $user;
    }

    /**
     * @after
     */
    public function tearDownFixtures()
    {
        unset($this->modx->pluginCache[self::PLUGIN_ID], $this->modx->eventMap['OnWebLogin']);
        $this->modx->unsetPlaceholder('unittest.weblogin.event_user_id');
        $this->modx->unsetPlaceholder('unittest.weblogin.get_user_id');

        $this->removeFixtures();
        $this->modx->error->reset();

        // Restore a non-anonymous fixture user for later tests in the harness.
        $this->modx->user = $this->modx->newObject(modUser::class);
        $this->modx->user->set('id', $this->modx->getOption('modx.test.user.id', null, 1));
        $this->modx->user->set('username', $this->modx->getOption('modx.test.user.username', null, 'test'));

        if (isset($_SESSION['modx.user.contextTokens']['web'])) {
            unset($_SESSION['modx.user.contextTokens']['web']);
        }
        if (isset($_SESSION['modx.web.user.token'])) {
            unset($_SESSION['modx.web.user.token']);
        }
        if (isset($_SESSION['modx.web.session.cookie.lifetime'])) {
            unset($_SESSION['modx.web.session.cookie.lifetime']);
        }
        if (isset($_SESSION['modx.web.user.config'])) {
            unset($_SESSION['modx.web.user.config']);
        }

        parent::tearDownFixtures();
    }

    protected function removeFixtures()
    {
        $existing = $this->modx->getObject(modUser::class, ['username' => self::USERNAME]);
        if ($existing) {
            $existing->remove();
        }
    }

    /**
     * afterLogin() must refresh $modx->user before OnWebLogin so getUser()
     * matches the authenticated user, not the previous anonymous cache.
     */
    public function testAfterLoginRefreshesModxUserForWebContext()
    {
        // CLI harness often leaves sessions uninitialized; seed so addSessionContext can write tokens.
        if (!isset($_SESSION) || !is_array($_SESSION)) {
            $_SESSION = [];
        }
        $this->assertTrue($this->modx->startSession(), 'Test requires a usable session for login context tokens');

        // Simulate a request that already resolved an anonymous visitor.
        $anonymous = $this->modx->newObject(modUser::class);
        $anonymous->fromArray([
            'id' => 0,
            'username' => '(anonymous)',
        ], '', true);
        $this->modx->user = $anonymous;

        /** @var Login $processor */
        $processor = Login::getInstance($this->modx, Login::class, [
            'username' => self::USERNAME,
            'password' => 'unit-test-login-password',
            'login_context' => 'web',
            'rememberme' => false,
        ]);
        $this->assertTrue($processor->initialize());

        // Skip password auth; exercise afterLogin() with the authenticated user loaded.
        $this->user = $this->modx->getObjectGraph(
            modUser::class,
            '{"Profile":{}}',
            ['modUser.username' => self::USERNAME]
        );
        $this->assertInstanceOf(modUser::class, $this->user);
        $processor->user = $this->user;

        $this->modx->eventMap['OnWebLogin'] = [self::PLUGIN_ID => (string)self::PLUGIN_ID];
        $this->modx->pluginCache[self::PLUGIN_ID] = [
            'id' => self::PLUGIN_ID,
            'name' => 'UnitTestOnWebLoginGetUser',
            'description' => '',
            'plugincode' => '$modx->setPlaceholder("unittest.weblogin.event_user_id", $user->get("id"));
$modx->setPlaceholder("unittest.weblogin.get_user_id", $modx->getUser()->get("id"));
',
            'locked' => false,
            'properties' => 'a:0:{}',
            'disabled' => false,
            'moduleguid' => '',
            'static' => false,
            'static_file' => '',
            'category' => 0,
        ];

        $response = $processor->afterLogin();
        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
        $this->assertTrue((bool)$response['success'], 'afterLogin should succeed for a valid web session context');

        $expectedId = (int)$this->user->get('id');
        $this->assertSame(
            $expectedId,
            (int)$this->modx->getPlaceholder('unittest.weblogin.event_user_id'),
            'OnWebLogin $user argument must be the authenticated user'
        );
        $this->assertSame(
            $expectedId,
            (int)$this->modx->getPlaceholder('unittest.weblogin.get_user_id'),
            'OnWebLogin $modx->getUser() must return the authenticated user, not anonymous/previous'
        );
        $this->assertSame(
            $expectedId,
            (int)$this->modx->getUser('web')->get('id'),
            'After web login, $modx->getUser(web) must remain the authenticated user'
        );
    }
}
