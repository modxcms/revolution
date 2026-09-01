<?php

namespace MODX\Revolution\Tests\Controllers;

use MODX\Revolution\MODxTestCase;

/**
 * Tests for Media access policy fixes (#14468)
 *
 * @package modx-test
 * @group Controllers
 */
class MediaAccessPolicyTest extends MODxTestCase
{
    public function testMediaParentMenuHasNoFileManagerPermissionInTransport()
    {
        $menusFile = dirname(__DIR__, 3) . '/data/transport.core.menus.php';
        $this->assertFileExists($menusFile);

        $contents = file_get_contents($menusFile);
        $this->assertMatchesRegularExpression(
            "/'text' => 'media',\s*\n\s*'description' => '',\s*\n\s*'permissions' => '',/",
            $contents,
            'Media parent menu must not require file_manager; child items enforce their own permissions.'
        );
        $this->assertMatchesRegularExpression(
            "/'text' => 'file_browser',[\s\S]*?'permissions' => 'file_manager',/",
            $contents,
            'Media Browser child must still require file_manager.'
        );
    }

    public function testBrowserFileGetUsesViewSourcePolicy()
    {
        $file = MODX_CORE_PATH . 'src/Revolution/Processors/Browser/File/Get.php';
        $contents = file_get_contents($file);
        $this->assertStringContainsString("public \$policy = 'view';", $contents);
        $this->assertStringNotContainsString("checkPolicy('delete')", $contents);
    }

    public function testFileTreePageLinkRequiresFileViewPermission()
    {
        $sourceFile = MODX_CORE_PATH . 'src/Revolution/Sources/modMediaSource.php';
        $contents = file_get_contents($sourceFile);
        $this->assertStringContainsString(
            "&& \$this->hasPermission('file_view')\n            && \$canView",
            $contents
        );
    }

    public function testDirectoryChmodLexiconDescribesVisibility()
    {
        $lexiconFile = MODX_CORE_PATH . 'lexicon/en/permissions.inc.php';
        $contents = file_get_contents($lexiconFile);
        $this->assertStringContainsString('set directory visibility', $contents);
        $this->assertStringContainsString('Does not include uploading files', $contents);
        $this->assertStringContainsString('Does not include creating empty files', $contents);
    }
}
