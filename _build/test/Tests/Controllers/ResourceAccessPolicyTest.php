<?php

namespace MODX\Revolution\Tests\Controllers;

use MODX\Revolution\MODxTestCase;

/**
 * Tests for Resource access policy accuracy (#14479)
 *
 * @package modx-test
 * @group Controllers
 */
class ResourceAccessPolicyTest extends MODxTestCase
{
    public function testPublishDocumentLexiconIsPublishOnly()
    {
        $lexiconFile = MODX_CORE_PATH . 'lexicon/en/permissions.inc.php';
        $contents = file_get_contents($lexiconFile);
        $this->assertStringContainsString(
            "\$_lang['perm.publish_document_desc'] = 'To publish any Resource. Unpublishing uses unpublish_document.';",
            $contents
        );
        $this->assertStringNotContainsString(
            'To publish or unpublish any Resource.',
            $contents
        );
    }

    public function testEditLockedLexiconDescribesElementsNotResources()
    {
        $lexiconFile = MODX_CORE_PATH . 'lexicon/en/permissions.inc.php';
        $contents = file_get_contents($lexiconFile);
        $this->assertStringContainsString('edit Elements that have the Locked checkbox', $contents);
        $this->assertStringContainsString('Does not apply to Resource session locks', $contents);
    }

    public function testDeleteDocumentLexiconDescribesSoftDelete()
    {
        $lexiconFile = MODX_CORE_PATH . 'lexicon/en/permissions.inc.php';
        $contents = file_get_contents($lexiconFile);
        $this->assertStringContainsString('soft-delete any Resource', $contents);
        $this->assertStringContainsString('requires purge_deleted', $contents);
    }

    public function testViewDocumentLexiconDistinguishesFromGetList()
    {
        $lexiconFile = MODX_CORE_PATH . 'lexicon/en/permissions.inc.php';
        $contents = file_get_contents($lexiconFile);
        $this->assertStringContainsString('Resource/Get and GetList use the generic view permission', $contents);
    }

    /**
     * @dataProvider providerClassMapGatedFiles
     */
    public function testClassKeyFieldIsGatedOnClassMap(string $relativePath)
    {
        $file = MODX_MANAGER_PATH . $relativePath;
        $this->assertFileExists($file);
        $contents = file_get_contents($file);
        $this->assertMatchesRegularExpression(
            '/MODx\.perm\.class_map\s*\n?\s*\?\s*\{[\s\S]*?modx-combo-class-derivatives[\s\S]*?:\s*\{[\s\S]*?xtype:\s*[\'"]hidden[\'"]/',
            $contents,
            $relativePath . ' must choose combo vs hidden from MODx.perm.class_map'
        );
    }

    public function providerClassMapGatedFiles(): array
    {
        return [
            ['assets/modext/widgets/resource/modx.panel.resource.js'],
            ['assets/modext/widgets/resource/modx.window.resource.js'],
            ['assets/modext/widgets/resource/modx.tree.resource.js'],
        ];
    }

    public function testQuickCreateHasSingleClassKeyField()
    {
        $file = MODX_MANAGER_PATH . 'assets/modext/widgets/resource/modx.tree.resource.js';
        $contents = file_get_contents($file);
        $fnStart = strpos($contents, 'MODx.getQuickCreateResourceSettingsFields');
        $this->assertNotFalse($fnStart);
        $fnEnd = strpos($contents, 'MODx.getQRSettings', $fnStart);
        $this->assertNotFalse($fnEnd);
        $chunk = substr($contents, $fnStart, $fnEnd - $fnStart);
        $this->assertStringContainsString('MODx.perm.class_map', $chunk);
        $this->assertStringNotContainsString(
            'id: `modx-${id}-class_key`',
            $chunk,
            'Pre-existing duplicate hidden class_key field must stay removed'
        );
        $this->assertStringContainsString('id: `modx-${id}-class-key`', $chunk);
    }
}
