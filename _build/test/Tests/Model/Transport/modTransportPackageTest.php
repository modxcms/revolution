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

namespace MODX\Revolution\Tests\Model\Transport;

use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Transport\modTransportPackage;
use MODX\Revolution\Transport\mysql\modTransportPackage as modTransportPackageMysql;

/**
 * Tests related to the modTransportPackage class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Transport
 * @group modTransportPackage
 */
class modTransportPackageTest extends MODxTestCase
{
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     * Test base modTransportPackage::listPackages with filter parameter (returns empty array).
     */
    public function testListPackagesBaseWithFilter()
    {
        $result = modTransportPackage::listPackages($this->modx, 1, 10, 0, '', 'uninstalled');
        $this->assertIsArray($result);
        $this->assertArrayHasKey('collection', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertSame([], $result['collection']);
        $this->assertSame(0, $result['total']);
    }

    /**
     * Test mysql modTransportPackage::listPackages without filter.
     */
    public function testListPackagesMysqlWithoutFilter()
    {
        $result = modTransportPackageMysql::listPackages($this->modx, 1, 10, 0, '', '');
        $this->assertIsArray($result);
        $this->assertArrayHasKey('collection', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertIsArray($result['collection']);
        $this->assertIsInt($result['total']);
    }

    /**
     * Test mysql modTransportPackage::listPackages with filter=uninstalled.
     */
    public function testListPackagesMysqlWithFilterUninstalled()
    {
        $result = modTransportPackageMysql::listPackages($this->modx, 1, 10, 0, '', 'uninstalled');
        $this->assertIsArray($result);
        $this->assertArrayHasKey('collection', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertIsArray($result['collection']);
        $this->assertIsInt($result['total']);
    }
}
