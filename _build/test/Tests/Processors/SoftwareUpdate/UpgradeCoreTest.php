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
}
