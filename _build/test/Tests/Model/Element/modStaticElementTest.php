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
namespace MODX\Revolution\Tests\Model\Element;

use MODX\Revolution\modSnippet;
use MODX\Revolution\modX;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\MODxTestHarness;
use MODX\Revolution\Sources\modFileMediaSource;
use MODX\Revolution\Sources\modMediaSource;

/**
 * Tests related to the modTag class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Element
 * @group modTag
 */
class modStaticElementTest extends MODxTestCase {
    public function setUp()
    {
        $this->modx = MODxTestHarness::getFixture(modX::class, 'modx');
    }

    public function testStaticElement()
    {
        $rc = [
            'recalculate_source_file' => true,
        ];
        /** @var modSnippet $el */
        $el = $this->modx->newObject(modSnippet::class);
        $el->fromArray([
            'static' => false,
            'static_file' => MODX_BASE_PATH.'_build/test/data/snippets/modSnippetTest/modSnippetTest.snippet.php'
        ]);

        self::assertFalse($el->isStatic());
        self::assertFalse($el->getSourceFile($rc));

        $el->set('static', true);
        self::assertTrue($el->isStatic());
        self::assertEquals(MODX_BASE_PATH . '_build/test/data/snippets/modSnippetTest/modSnippetTest.snippet.php', $el->getSourceFile($rc));

        /** @var modMediaSource $source */
        $source = $this->modx->newObject(modFileMediaSource::class);
        $source->setProperties([
            'basePath' => ['name' => 'basePath', 'value' => '_build/test/data'],
            'basePathRelative' => ['name' => 'basePathRelative', 'value' => true],
        ]);
        $source->save();

        $el->set('source', $source->get('id'));
        $el->set('static_file', 'snippets/modSnippetTest/modSnippetTest.snippet.php');

        self::assertTrue($el->isStatic());
        self::assertEquals(MODX_BASE_PATH . '_build/test/data/snippets/modSnippetTest/modSnippetTest.snippet.php', $el->getSourceFile($rc));
        $source->set('is_stream', false);
        $source->save();

        $el->setSource($source);

        self::assertTrue($el->isStatic());
        self::assertEquals('snippets/modSnippetTest/modSnippetTest.snippet.php', $el->getSourceFile($rc));
        $source->remove();
    }
}
