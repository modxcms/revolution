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
use MODX\Revolution\Processors\Security\Logout;

/**
 * Ensures Logout::process() refreshes $modx->user before OnWebLogout so
 * $modx->getUser() reflects logged-out state while event $user stays who left.
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Security
 * @group Logout
 */
class LogoutTest extends MODxTestCase
{
    public const USERNAME = 'unit-test-logout-getuser';
    public const PLUGIN_ID = 99917015;

    /** @var modUser */
    protected $user;

    /** @var array|null */
    protected $originalEventMap;

    /** @var array */
    protected $originalPluginCache = [];

    /**
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->removeFixtures();
        $this->modx->error->reset();
        $this->originalEventMap = $this->modx->eventMap;
        $this->originalPluginCache = $this->modx->pluginCache;
        $this->clearPluginScriptCache();

        $user = $this->modx->newObject(modUser::class);
        $user->fromArray([
            'username' => self::USERNAME,
            'active' => true,
        ]);
        $user->set('password', 'unit-test-logout-password');

        $profile = $this->modx->newObject(modUserProfile::class);
        $profile->fromArray([
            'email' => 'unit-test-logout-getuser@example.com',
            'fullname' => 'Unit Test Logout',
            'failedlogincount' => 0,
            'blocked' => 0,
        ]);
        $user->addOne($profile, 'Profile');

        if (!$user->save()) {
            $this->fail('Could not save fixture user for LogoutTest');
        }
        $this->user = $user;
    }

    /**
     * @after
     */
    public function tearDownFixtures()
    {
        $this->modx->eventMap = $this->originalEventMap;
        $this->modx->pluginCache = $this->originalPluginCache;
        $this->modx->unsetPlaceholder('unittest.weblogout.event_user_id');
        $this->modx->unsetPlaceholder('unittest.weblogout.event_username');
        $this->modx->unsetPlaceholder('unittest.weblogout.get_user_id');
        $this->modx->unsetPlaceholder('unittest.weblogout.authenticated');

        $this->removeFixtures();
        $this->modx->error->reset();

        if (!isset($_SESSION) || !is_array($_SESSION)) {
            $_SESSION = [];
        }
        $this->modx->startSession();

        $this->modx->user = $this->modx->newObject(modUser::class);
        $this->modx->user->set('id', $this->modx->getOption('modx.test.user.id', null, 1));
        $this->modx->user->set('username', $this->modx->getOption('modx.test.user.username', null, 'test'));

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
     * Drop cached include/script for this synthetic plugin id so plugincode from
     * pluginCache is what actually runs (other tests may reuse nearby ids).
     */
    protected function clearPluginScriptCache()
    {
        $cachePath = $this->modx->getCachePath();
        $paths = [
            $cachePath . 'includes/elements/modx/revolution/modplugin/' . self::PLUGIN_ID . '.include.cache.php',
            $cachePath . 'scripts/elements/modx/revolution/modplugin/' . self::PLUGIN_ID . '.cache.php',
        ];
        foreach ($paths as $path) {
            if (is_file($path)) {
                unlink($path);
            }
        }
    }

    /**
     * Seed web auth without session_regenerate_id / cookie side effects.
     */
    protected function seedWebSession(modUser $user)
    {
        if (!isset($_SESSION) || !is_array($_SESSION)) {
            $_SESSION = [];
        }
        $this->modx->startSession();

        $userId = (int)$user->get('id');
        $_SESSION['modx.user.contextTokens'] = ['web' => $userId];
        $_SESSION['modx.web.user.token'] = 'unit-test-logout-token';
        $user->getSessionContexts();
    }

    /**
     * After removeSessionContexts(), OnWebLogout must see anonymous via getUser()
     * while event $user / userid remain the user who logged out.
     */
    public function testProcessRefreshesModxUserBeforeOnWebLogout()
    {
        $this->user = $this->modx->getObjectGraph(
            modUser::class,
            '{"Profile":{}}',
            ['modUser.username' => self::USERNAME]
        );
        $this->assertInstanceOf(modUser::class, $this->user);

        // Authenticate only in web (no mgr token) so getUser() cannot fall back to mgr.
        $this->seedWebSession($this->user);
        $this->modx->user = $this->user;
        $this->assertTrue($this->modx->user->isAuthenticated('web'));

        /** @var Logout $processor */
        $processor = Logout::getInstance($this->modx, Logout::class, [
            'login_context' => 'web',
        ]);
        $this->assertTrue($processor->initialize());

        $this->modx->eventMap['OnWebLogout'] = [self::PLUGIN_ID => (string)self::PLUGIN_ID];
        $this->modx->pluginCache[self::PLUGIN_ID] = [
            'id' => self::PLUGIN_ID,
            'name' => 'UnitTestOnWebLogoutGetUser',
            'description' => '',
            'plugincode' => '$modx->setPlaceholder("unittest.weblogout.event_user_id", $user->get("id"));
$modx->setPlaceholder("unittest.weblogout.event_username", $user->get("username"));
$modx->setPlaceholder("unittest.weblogout.get_user_id", $modx->getUser()->get("id"));
$modx->setPlaceholder("unittest.weblogout.authenticated", $modx->user->isAuthenticated("web") ? "1" : "0");
',
            'locked' => false,
            'properties' => 'a:0:{}',
            'disabled' => false,
            'moduleguid' => '',
            'static' => false,
            'static_file' => '',
            'category' => 0,
        ];

        $response = $processor->process();
        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
        $this->assertTrue((bool)$response['success'], 'Logout should succeed for an authenticated web session');

        $expectedId = (int)$this->user->get('id');
        $this->assertSame(
            $expectedId,
            (int)$this->modx->getPlaceholder('unittest.weblogout.event_user_id'),
            'OnWebLogout $user argument must remain the user who logged out'
        );
        $this->assertSame(
            self::USERNAME,
            (string)$this->modx->getPlaceholder('unittest.weblogout.event_username'),
            'OnWebLogout username argument must remain the user who logged out'
        );
        $this->assertSame(
            0,
            (int)$this->modx->getPlaceholder('unittest.weblogout.get_user_id'),
            'OnWebLogout $modx->getUser() must reflect logged-out/anonymous state'
        );
        $this->assertSame(
            '0',
            (string)$this->modx->getPlaceholder('unittest.weblogout.authenticated'),
            'OnWebLogout must not treat the request as authenticated in web'
        );
    }
}
