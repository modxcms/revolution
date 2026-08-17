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
namespace MODX\Revolution\Tests\Processors\Workspace\Packages;

use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Workspace\Packages\CheckForUpdates;
use MODX\Revolution\Transport\modTransportPackage;

/**
 * Tests related to Workspace/Packages/CheckForUpdates.
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Workspace
 * @group Packages
 * @group CheckForUpdates
 */
class CheckForUpdatesTest extends MODxTestCase
{
    private const SIGNATURE = 'unittestpackage-1.0.0-pl';

    /**
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->modx->lexicon->load('workspace');
        $this->removeTestPackage();

        /** @var modTransportPackage $package */
        $package = $this->modx->newObject(modTransportPackage::class);
        $package->fromArray([
            'signature' => self::SIGNATURE,
            'package_name' => 'unittestpackage',
            'provider' => 0,
            'workspace' => 1,
            'state' => 1,
        ], '', true, true);
        $package->save();
    }

    /**
     * @after
     */
    public function tearDownFixtures()
    {
        $this->removeTestPackage();
        $this->modx->error->reset();
        parent::tearDownFixtures();
    }

    private function removeTestPackage(): void
    {
        $package = $this->modx->getObject(modTransportPackage::class, self::SIGNATURE);
        if ($package) {
            $package->remove();
        }
    }

    public function testMissingPackageFails(): void
    {
        $result = $this->modx->runProcessor(CheckForUpdates::class, [
            'signature' => 'missing-package-1.0.0-pl',
        ]);
        $this->assertFalse($this->checkForSuccess($result));
        $this->assertSame(
            $this->modx->lexicon('package_err_nf'),
            $result->getMessage()
        );
    }

    public function testPackageWithoutProviderIsUpToDateSuccess(): void
    {
        $result = $this->modx->runProcessor(CheckForUpdates::class, [
            'signature' => self::SIGNATURE,
        ]);
        $this->assertTrue($this->checkForSuccess($result));
        $this->assertSame(
            $this->modx->lexicon('package_err_uptodate', ['signature' => self::SIGNATURE]),
            $result->getMessage()
        );
        $this->assertSame([], $result->getObject());
    }

    public function testProviderWithNoUpdatesIsUpToDateSuccess(): void
    {
        $processor = new CheckForUpdates($this->modx, [
            'signature' => self::SIGNATURE,
        ]);
        $this->assertTrue($processor->initialize());

        $processor->provider = new class {
            public function latest(string $identifier, string $constraint = '*', array $args = []): array
            {
                return [];
            }

            public function get($key)
            {
                return $key === 'name' ? 'UnitTestProvider' : null;
            }
        };

        $response = $processor->process();
        $this->assertIsArray($response);
        $this->assertTrue($response['success']);
        $this->assertSame(
            $this->modx->lexicon('package_err_uptodate', ['signature' => self::SIGNATURE]),
            $response['message']
        );
        $this->assertSame([], $response['object']);
    }
}
