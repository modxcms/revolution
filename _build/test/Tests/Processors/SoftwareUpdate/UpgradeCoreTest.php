<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 *
 * @package modx-test
 */
namespace MODX\Revolution\Tests\Processors\SoftwareUpdate;

use MODX\Revolution\Processors\ProcessorResponse;
use MODX\Revolution\Processors\SoftwareUpdate\UpgradeCore;
use MODX\Revolution\MODxTestCase;

/**
 * Tests for SoftwareUpdate/UpgradeCore processor (validation and permission).
 *
 * @package modx-test
 * @subpackage Processors
 * @group Processors
 * @group SoftwareUpdate
 * @group SoftwareUpdateProcessors
 */
class UpgradeCoreTest extends MODxTestCase
{
    /**
     * Test that UpgradeCore fails with empty downloadId.
     */
    public function testUpgradeCoreFailsWithEmptyDownloadId()
    {
        $result = $this->modx->runProcessor(UpgradeCore::class, [
            'downloadId' => '',
        ]);
        $this->assertInstanceOf(ProcessorResponse::class, $result);
        $this->assertTrue($result->isError());
    }

    /**
     * Test that UpgradeCore fails with invalid downloadId format (not a UUID).
     */
    public function testUpgradeCoreFailsWithInvalidDownloadId()
    {
        $result = $this->modx->runProcessor(UpgradeCore::class, [
            'downloadId' => 'not-a-valid-uuid',
        ]);
        $this->assertInstanceOf(ProcessorResponse::class, $result);
        $this->assertTrue($result->isError());
    }

    /**
     * Test that UpgradeCore fails with malformed downloadId.
     */
    public function testUpgradeCoreFailsWithMalformedDownloadId()
    {
        $result = $this->modx->runProcessor(UpgradeCore::class, [
            'downloadId' => 'abc',
        ]);
        $this->assertInstanceOf(ProcessorResponse::class, $result);
        $this->assertTrue($result->isError());
    }

    /**
     * Test that UpgradeCore fails when user lacks upgrade_core and is not in allowed groups.
     */
    public function testUpgradeCoreFailsWhenUserLacksPermission()
    {
        $originalUser = $this->modx->user;
        $originalAllowedGroups = $this->modx->getOption('core_upgrade_allowed_groups', null, 'Administrator');

        $restrictedUser = $this->modx->newObject(\MODX\Revolution\modUser::class);
        $restrictedUser->set('id', 999999);
        $restrictedUser->set('username', 'restricted_test_user');
        $this->modx->user = $restrictedUser;

        $this->modx->setOption('core_upgrade_allowed_groups', '');

        $result = $this->modx->runProcessor(UpgradeCore::class, [
            'downloadId' => '11111111-1111-1111-1111-111111111111',
        ]);

        $this->modx->user = $originalUser;
        $this->modx->setOption('core_upgrade_allowed_groups', $originalAllowedGroups);

        $this->assertInstanceOf(ProcessorResponse::class, $result);
        $this->assertTrue($result->isError());
    }

    /**
     * Test that UpgradeCore fails when GetFile returns no zip URL (retrieve error path).
     */
    public function testUpgradeCoreFailsOnRetrieveError()
    {
        $result = $this->modx->runProcessor(UpgradeCore::class, [
            'downloadId' => '00000000-0000-0000-0000-000000000000',
        ]);
        $this->assertInstanceOf(ProcessorResponse::class, $result);
        $this->assertTrue($result->isError());
    }
}
