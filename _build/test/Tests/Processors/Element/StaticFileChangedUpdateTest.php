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

namespace MODX\Revolution\Tests\Processors\Element;

use MODX\Revolution\modChunk;
use MODX\Revolution\modPlugin;
use MODX\Revolution\modSnippet;
use MODX\Revolution\modTemplate;
use MODX\Revolution\modTemplateVar;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Element\Chunk\Update as ChunkUpdate;
use MODX\Revolution\Processors\Element\Plugin\Update as PluginUpdate;
use MODX\Revolution\Processors\Element\Snippet\Update as SnippetUpdate;
use MODX\Revolution\Processors\Element\Template\Update as TemplateUpdate;
use MODX\Revolution\Processors\Element\TemplateVar\Update as TemplateVarUpdate;

/**
 * Ensures static_file_changed is returned for every element Update processor (#13235).
 *
 * @group Processors
 * @group Element
 */
class StaticFileChangedUpdateTest extends MODxTestCase
{
    /** @var string */
    private $tmpDir;

    /**
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->tmpDir = sys_get_temp_dir() . '/modx-static-file-changed-' . uniqid('', true);
        mkdir($this->tmpDir, 0777, true);
    }

    /**
     * @after
     */
    public function tearDownFixtures()
    {
        parent::tearDownFixtures();
        foreach (
            [
                modChunk::class,
                modSnippet::class,
                modPlugin::class,
                modTemplate::class,
                modTemplateVar::class,
            ] as $class
        ) {
            $objects = $this->modx->getCollection($class, [
                'name:LIKE' => 'UnitTestStaticFile%',
            ]);
            if ($class === modTemplate::class) {
                $objects = $this->modx->getCollection($class, [
                    'templatename:LIKE' => 'UnitTestStaticFile%',
                ]);
            }
            foreach ($objects as $object) {
                $object->remove();
            }
        }
        if ($this->tmpDir && is_dir($this->tmpDir)) {
            foreach (glob($this->tmpDir . '/*') ?: [] as $file) {
                @unlink($file);
            }
            @rmdir($this->tmpDir);
        }
        $this->modx->error->reset();
    }

    /**
     * @dataProvider providerElementTypes
     */
    public function testStaticFileChangedFlagOnPathChange(
        string $classKey,
        string $processor,
        string $nameField,
        string $contentField,
        string $contentValue
    ) {
        $fileA = $this->tmpDir . '/a.' . substr(md5($classKey), 0, 6) . '.txt';
        $fileB = $this->tmpDir . '/b.' . substr(md5($classKey), 0, 6) . '.txt';
        file_put_contents($fileA, $contentValue);
        file_put_contents($fileB, $contentValue);

        /** @var \MODX\Revolution\modElement $element */
        $element = $this->modx->newObject($classKey);
        $element->fromArray([
            $nameField => 'UnitTestStaticFile' . substr(md5($classKey), 0, 6),
            'description' => 'static file changed test',
            'static' => true,
            'static_file' => $fileA,
            $contentField => $contentValue,
        ]);
        $this->assertTrue((bool)$element->save(), 'Failed to create static ' . $classKey);

        $payload = [
            'id' => $element->get('id'),
            $nameField => $element->get($nameField),
            'description' => $element->get('description'),
            'category' => 0,
            'locked' => false,
            'static' => 1,
            'static_file' => $fileB,
            $contentField => $contentValue,
            'clearCache' => false,
        ];
        if ($classKey === modPlugin::class) {
            $payload['disabled'] = false;
        }

        $result = $this->modx->runProcessor($processor, $payload);
        $this->assertTrue($this->checkForSuccess($result), $result->getMessage());
        $object = $result->getObject();
        $this->assertArrayHasKey('static_file_changed', $object);
        $this->assertTrue(
            (bool)$object['static_file_changed'],
            'Expected static_file_changed=true after path change for ' . $classKey
        );

        $payload['static_file'] = $fileB;
        $result = $this->modx->runProcessor($processor, $payload);
        $this->assertTrue($this->checkForSuccess($result), $result->getMessage());
        $object = $result->getObject();
        $this->assertArrayHasKey('static_file_changed', $object);
        $this->assertFalse(
            (bool)$object['static_file_changed'],
            'Expected static_file_changed=false when path unchanged for ' . $classKey
        );
    }

    public function providerElementTypes(): array
    {
        return [
            'chunk' => [modChunk::class, ChunkUpdate::class, 'name', 'snippet', '<p>static</p>'],
            'snippet' => [modSnippet::class, SnippetUpdate::class, 'name', 'snippet', 'return "static";'],
            'plugin' => [modPlugin::class, PluginUpdate::class, 'name', 'plugincode', 'return true;'],
            'template' => [modTemplate::class, TemplateUpdate::class, 'templatename', 'content', '<html>static</html>'],
            'tv' => [modTemplateVar::class, TemplateVarUpdate::class, 'name', 'default_text', 'static'],
        ];
    }
}
