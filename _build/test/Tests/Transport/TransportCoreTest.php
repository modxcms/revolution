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
namespace MODX\Revolution\Tests\Transport;


use MODX\Revolution\MODxTestCase;

/**
 * Tests related to creating transport packages
 *
 * @package modx-test
 * @subpackage modx
 * @group Transport
 */
class TransportCoreTest extends MODxTestCase
{

    public function setUp()
    {
        parent::setUp();

        if (!defined('MODX_SOURCE_PATH')) {
            define('MODX_SOURCE_PATH', dirname(__DIR__) . '/../../../');
        }

        if (!defined('MODX_BUILD_DIR')) {
            define('MODX_BUILD_DIR', MODX_SOURCE_PATH . '_build/');
        }

        if (!defined('MODX_PACKAGES_PATH')) {
            define('MODX_PACKAGES_PATH', MODX_SOURCE_PATH . 'core/packages/');
        }
    }

    public function tearDown()
    {
        @unlink(MODX_PACKAGES_PATH. "core.transport.zip");
    }

    public function testBuildCoreTransportPackage()
    {
        $transportCoreFile = MODX_BUILD_DIR. 'transport.core.php';
        $transportCorePackFile = MODX_PACKAGES_PATH. 'core.transport.zip';

        $result = shell_exec("php $transportCoreFile");
        $this->assertContains('Transport zip created. Build script finished.', $result);

        $this->assertFileExists($transportCorePackFile);
    }
}
