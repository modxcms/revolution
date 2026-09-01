<?php

namespace MODX\Revolution\Tests\Controllers\System;

use MODX\Revolution\MODxControllerTestCase;

/**
 * Tests for system/file/edit controller (#14468 file_update gate)
 *
 * @package modx-test
 * @group Controllers
 */
class SystemFileEditControllerTest extends MODxControllerTestCase
{
    /** @var \SystemFileEditManagerController $controller */
    public $controller;

    public $controllerName = 'SystemFileEditManagerController';
    public $controllerPath = 'system/file/edit';

    public function testCheckPermissionsRequiresFileView()
    {
        $this->assertTrue($this->controller->checkPermissions());
    }

    public function testCanSaveGatesOnFileUpdateAndSourceSavePolicy()
    {
        $controllerFile = MODX_MANAGER_PATH . 'controllers/default/system/file/edit.class.php';
        $contents = file_get_contents($controllerFile);
        $this->assertStringContainsString(
            "\$this->canSave = \$this->modx->hasPermission('file_update') && \$source->checkPolicy('save');",
            $contents
        );
        $this->assertStringContainsString("\$source->checkPolicy('view')", $contents);
        $this->assertStringNotContainsString("\$this->canSave = true;", $contents);
    }

    public function testMediaBrowserViewDefinesUnpackFileHandler()
    {
        $browserFile = MODX_MANAGER_PATH . 'assets/modext/widgets/media/modx.browser.js';
        $contents = file_get_contents($browserFile);
        $this->assertStringContainsString('unpackFile: function', $contents);
        $this->assertStringContainsString('file: data.pathRelative', $contents);
    }
}
