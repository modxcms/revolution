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

namespace MODX\Revolution\Tests\Model\Security;

use MODX\Revolution\modContext;
use MODX\Revolution\modContextSetting;
use MODX\Revolution\MODxTestCase;

/**
 * Regression for #16424: mgr must keep anonymous_sessions enabled even when
 * the system setting is disabled, so manager login can start a session without
 * an existing cookie.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group modX
 * @group Session
 */
class AnonymousSessionsContextTest extends MODxTestCase
{
    /** @var mixed */
    private $originalSystemAnonymousSessions;

    /** @var mixed */
    private $originalMgrAnonymousSessions;

    /** @var bool */
    private $createdMgrSetting = false;

    /** @var bool */
    private $createdWebSetting = false;

    /**
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();

        $this->originalSystemAnonymousSessions = $this->modx->getOption('anonymous_sessions');
        $this->modx->setOption('anonymous_sessions', false);
        $this->modx->_systemConfig['anonymous_sessions'] = false;

        $mgrSetting = $this->modx->getObject(modContextSetting::class, [
            'context_key' => modContext::CONTEXT_MANAGER,
            'key' => 'anonymous_sessions',
        ]);
        if (!$mgrSetting) {
            $mgrSetting = $this->modx->newObject(modContextSetting::class);
            $mgrSetting->fromArray([
                'context_key' => modContext::CONTEXT_MANAGER,
                'key' => 'anonymous_sessions',
                'value' => true,
                'xtype' => 'combo-boolean',
                'namespace' => 'core',
                'area' => 'session',
            ], '', true, true);
            $this->assertTrue((bool)$mgrSetting->save(), 'Could not create mgr anonymous_sessions fixture.');
            $this->createdMgrSetting = true;
            $this->originalMgrAnonymousSessions = null;
        } else {
            $this->originalMgrAnonymousSessions = $mgrSetting->get('value');
            $mgrSetting->set('value', true);
            $this->assertTrue((bool)$mgrSetting->save(), 'Could not enable mgr anonymous_sessions fixture.');
        }

        $webSetting = $this->modx->getObject(modContextSetting::class, [
            'context_key' => modContext::CONTEXT_DEFAULT,
            'key' => 'anonymous_sessions',
        ]);
        if ($webSetting) {
            $webSetting->remove();
        }
        $webSetting = $this->modx->newObject(modContextSetting::class);
        $webSetting->fromArray([
            'context_key' => modContext::CONTEXT_DEFAULT,
            'key' => 'anonymous_sessions',
            'value' => false,
            'xtype' => 'combo-boolean',
            'namespace' => 'core',
            'area' => 'session',
        ], '', true, true);
        $this->assertTrue((bool)$webSetting->save(), 'Could not create web anonymous_sessions fixture.');
        $this->createdWebSetting = true;

        $this->refreshContextSettings();
    }

    /**
     * @after
     */
    public function tearDownFixtures()
    {
        if ($this->createdMgrSetting) {
            $mgrSetting = $this->modx->getObject(modContextSetting::class, [
                'context_key' => modContext::CONTEXT_MANAGER,
                'key' => 'anonymous_sessions',
            ]);
            if ($mgrSetting) {
                $mgrSetting->remove();
            }
        } elseif ($this->originalMgrAnonymousSessions !== null) {
            $mgrSetting = $this->modx->getObject(modContextSetting::class, [
                'context_key' => modContext::CONTEXT_MANAGER,
                'key' => 'anonymous_sessions',
            ]);
            if ($mgrSetting) {
                $mgrSetting->set('value', $this->originalMgrAnonymousSessions);
                $mgrSetting->save();
            }
        }

        if ($this->createdWebSetting) {
            $webSetting = $this->modx->getObject(modContextSetting::class, [
                'context_key' => modContext::CONTEXT_DEFAULT,
                'key' => 'anonymous_sessions',
            ]);
            if ($webSetting) {
                $webSetting->remove();
            }
        }

        $this->modx->setOption('anonymous_sessions', $this->originalSystemAnonymousSessions);
        $this->modx->_systemConfig['anonymous_sessions'] = $this->originalSystemAnonymousSessions;
        $this->refreshContextSettings();
        $this->modx->reloadContext(modContext::CONTEXT_DEFAULT);

        parent::tearDownFixtures();
    }

    public function testManagerContextOverridesSystemAnonymousSessionsFalse()
    {
        $this->assertTrue(
            $this->activateContext(modContext::CONTEXT_MANAGER),
            'Could not activate mgr context.'
        );
        $this->assertTrue(
            $this->modx->paramValueIsTrue(
                ['anonymous_sessions' => $this->modx->getOption('anonymous_sessions')],
                'anonymous_sessions'
            ),
            'mgr must enable anonymous_sessions so login can start a session without a cookie.'
        );
    }

    public function testWebContextCanDisableAnonymousSessions()
    {
        $this->assertTrue(
            $this->activateContext(modContext::CONTEXT_MANAGER),
            'Could not activate mgr context before returning to web.'
        );
        $this->assertTrue(
            $this->activateContext(modContext::CONTEXT_DEFAULT),
            'Could not activate web context.'
        );
        $this->assertFalse(
            $this->modx->paramValueIsTrue(
                ['anonymous_sessions' => $this->modx->getOption('anonymous_sessions')],
                'anonymous_sessions'
            ),
            'web context should be able to disable anonymous_sessions independently of mgr.'
        );
    }

    public function testUpgradeScriptEnsuresManagerAnonymousSessions()
    {
        $mgrSetting = $this->modx->getObject(modContextSetting::class, [
            'context_key' => modContext::CONTEXT_MANAGER,
            'key' => 'anonymous_sessions',
        ]);
        if ($mgrSetting) {
            $mgrSetting->remove();
        }
        $this->refreshContextSettings();

        $modx = $this->modx;
        $upgradeScript = dirname(__DIR__, 5) . '/setup/includes/upgrades/common/3.3.0-mgr-anonymous-sessions.php';
        include $upgradeScript;

        $mgrSetting = $this->modx->getObject(modContextSetting::class, [
            'context_key' => modContext::CONTEXT_MANAGER,
            'key' => 'anonymous_sessions',
        ]);
        $this->assertInstanceOf(modContextSetting::class, $mgrSetting);
        $this->assertTrue(
            $this->modx->paramValueIsTrue(['value' => $mgrSetting->get('value')], 'value'),
            'Upgrade must create mgr anonymous_sessions=Yes.'
        );

        $mgrSetting->set('value', false);
        $this->assertTrue((bool)$mgrSetting->save());

        include $upgradeScript;

        $mgrSetting = $this->modx->getObject(modContextSetting::class, [
            'context_key' => modContext::CONTEXT_MANAGER,
            'key' => 'anonymous_sessions',
        ]);
        $this->assertTrue(
            $this->modx->paramValueIsTrue(['value' => $mgrSetting->get('value')], 'value'),
            'Upgrade must restore mgr anonymous_sessions=Yes if it was disabled.'
        );
        $this->createdMgrSetting = true;
    }

    private function activateContext(string $contextKey): bool
    {
        if ($this->modx->context instanceof modContext && $this->modx->context->get('key') === $contextKey) {
            return (bool)$this->modx->reloadContext($contextKey);
        }

        return (bool)$this->modx->switchContext($contextKey, true);
    }

    private function refreshContextSettings(): void
    {
        $this->modx->cacheManager->refresh([
            'context_settings' => [
                'contexts' => [modContext::CONTEXT_MANAGER, modContext::CONTEXT_DEFAULT],
            ],
        ]);
        unset($this->modx->contexts[modContext::CONTEXT_MANAGER], $this->modx->contexts[modContext::CONTEXT_DEFAULT]);
    }
}
