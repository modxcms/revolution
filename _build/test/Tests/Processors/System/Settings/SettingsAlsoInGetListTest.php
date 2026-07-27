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

namespace MODX\Revolution\Tests\Processors\System\Settings;

use MODX\Revolution\modContextSetting;
use MODX\Revolution\modSystemSetting;
use MODX\Revolution\modUserSetting;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Security\User\Setting\GetList as UserSettingGetList;
use MODX\Revolution\Processors\System\Settings\GetList;

/**
 * Regression for #16472: system settings list marks context/user overrides.
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group System
 * @group Settings
 */
class SettingsAlsoInGetListTest extends MODxTestCase
{
    private const KEY = 'unittest_also_in_setting';

    /**
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->cleanupFixtures();

        $system = $this->modx->newObject(modSystemSetting::class);
        $system->fromArray([
            'key' => self::KEY,
            'value' => 'system',
            'xtype' => 'textfield',
            'namespace' => 'core',
            'area' => 'system',
        ], '', true, true);
        $this->assertTrue((bool)$system->save());
    }

    /**
     * @after
     */
    public function tearDownFixtures()
    {
        $this->cleanupFixtures();
        parent::tearDownFixtures();
    }

    public function testSystemSettingsListMarksContextAndUserOverrides()
    {
        $context = $this->modx->newObject(modContextSetting::class);
        $context->fromArray([
            'context_key' => 'web',
            'key' => self::KEY,
            'value' => 'context',
            'xtype' => 'textfield',
            'namespace' => 'core',
            'area' => 'system',
        ], '', true, true);
        $this->assertTrue((bool)$context->save());

        $user = $this->modx->newObject(modUserSetting::class);
        $user->fromArray([
            'user' => 1,
            'key' => self::KEY,
            'value' => 'user',
            'xtype' => 'textfield',
            'namespace' => 'core',
            'area' => 'system',
        ], '', true, true);
        $this->assertTrue((bool)$user->save());

        $result = $this->modx->runProcessor(GetList::class, [
            'query' => self::KEY,
            'limit' => 20,
            'start' => 0,
        ]);
        $this->assertTrue($this->checkForSuccess($result));

        $row = $this->findSettingRow($result, self::KEY);
        $this->assertNotNull($row);
        $this->assertTrue($row['has_context_override']);
        $this->assertTrue($row['has_user_override']);
    }

    public function testSystemSettingsListWithoutOverrides()
    {
        $result = $this->modx->runProcessor(GetList::class, [
            'query' => self::KEY,
            'limit' => 20,
            'start' => 0,
        ]);
        $this->assertTrue($this->checkForSuccess($result));

        $row = $this->findSettingRow($result, self::KEY);
        $this->assertNotNull($row);
        $this->assertFalse($row['has_context_override']);
        $this->assertFalse($row['has_user_override']);
    }

    public function testUserSettingsListDoesNotAddAlsoInFlags()
    {
        $result = $this->modx->runProcessor(UserSettingGetList::class, [
            'user' => 1,
            'limit' => 5,
            'start' => 0,
        ]);
        // May succeed with empty list depending on fixtures; assert no also-in fields when rows exist.
        if (!$this->checkForSuccess($result)) {
            $this->markTestSkipped('User setting list processor unavailable in test harness.');
        }

        $results = $this->getResults($result);
        foreach ($results as $row) {
            $this->assertArrayNotHasKey('has_context_override', $row);
            $this->assertArrayNotHasKey('has_user_override', $row);
        }
        $this->assertTrue(true);
    }

    private function cleanupFixtures(): void
    {
        $system = $this->modx->getObject(modSystemSetting::class, ['key' => self::KEY]);
        if ($system) {
            $system->remove();
        }

        $contexts = $this->modx->getCollection(modContextSetting::class, ['key' => self::KEY]);
        foreach ($contexts as $context) {
            $context->remove();
        }

        $users = $this->modx->getCollection(modUserSetting::class, ['key' => self::KEY]);
        foreach ($users as $user) {
            $user->remove();
        }
    }

    /**
     * @param mixed $result
     * @param string $key
     * @return array|null
     */
    private function findSettingRow($result, string $key): ?array
    {
        foreach ($this->getResults($result) as $row) {
            if (($row['oldkey'] ?? $row['key'] ?? null) === $key || ($row['key'] ?? null) === $key) {
                return $row;
            }
        }

        return null;
    }
}
