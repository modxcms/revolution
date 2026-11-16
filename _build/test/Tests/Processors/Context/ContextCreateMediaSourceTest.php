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

namespace MODX\Revolution\Tests\Processors\Context;

use MODX\Revolution\modContext;
use MODX\Revolution\modTemplateVar;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Context\Create;
use MODX\Revolution\Sources\modFileMediaSource;
use MODX\Revolution\Sources\modMediaSource;
use MODX\Revolution\Sources\modMediaSourceElement;

/**
 * Regression for #9058: context create materializes default TV media source bindings.
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Context
 * @group ContextProcessors
 */
class ContextCreateMediaSourceTest extends MODxTestCase
{
    /** @var string */
    private $contextKey = 'unittestmediasrc';

    /**
     * @after
     */
    public function tearDownFixtures()
    {
        $context = $this->modx->getObject(modContext::class, ['key' => $this->contextKey]);
        if ($context) {
            $context->remove();
        }

        $tvs = $this->modx->getCollection(modTemplateVar::class, [
            'name:LIKE' => '%UnitTestCtxTv%',
        ]);
        /** @var modTemplateVar $tv */
        foreach ($tvs as $tv) {
            $elements = $this->modx->getCollection(modMediaSourceElement::class, [
                'object' => $tv->get('id'),
                'object_class' => modTemplateVar::class,
            ]);
            foreach ($elements as $element) {
                $element->remove();
            }
            $tv->remove();
        }

        $sources = $this->modx->getCollection(modMediaSource::class, [
            'name:LIKE' => '%UnitTestCtxSource%',
        ]);
        /** @var modMediaSource $source */
        foreach ($sources as $source) {
            $source->remove();
        }

        parent::tearDownFixtures();
    }

    public function testContextCreateAssignsDefaultMediaSourceToTvs()
    {
        $defaultSourceId = (int)$this->modx->getOption('default_media_source', null, 1);
        $defaultSource = $this->modx->getObject(modMediaSource::class, ['id' => $defaultSourceId]);
        if (!$defaultSource) {
            $this->markTestSkipped('default_media_source is not available in the test database.');
        }

        /** @var modMediaSource $customSource */
        $customSource = $this->modx->newObject(modMediaSource::class);
        $customSource->fromArray([
            'name' => 'UnitTestCtxSource',
            'class_key' => modFileMediaSource::class,
        ], '', true, true);
        $this->assertTrue((bool)$customSource->save(), 'Could not create custom media source fixture.');
        $this->assertNotEquals(
            $defaultSourceId,
            (int)$customSource->get('id'),
            'Custom source fixture must differ from default_media_source.'
        );

        /** @var modTemplateVar $tv */
        $tv = $this->modx->newObject(modTemplateVar::class);
        $tv->fromArray(['name' => 'UnitTestCtxTv'], '', true, true);
        $this->assertTrue((bool)$tv->save(), 'Could not create TV fixture.');

        foreach (['web', 'mgr'] as $contextKey) {
            /** @var modMediaSourceElement $binding */
            $binding = $this->modx->newObject(modMediaSourceElement::class);
            $binding->fromArray([
                'source' => $customSource->get('id'),
                'object_class' => modTemplateVar::class,
                'object' => $tv->get('id'),
                'context_key' => $contextKey,
            ], '', true, true);
            $this->assertTrue(
                (bool)$binding->save(),
                'Could not create media source binding for ' . $contextKey
            );
        }

        $result = $this->modx->runProcessor(Create::class, [
            'key' => $this->contextKey,
            'description' => 'Context create media source regression',
        ]);
        $this->assertTrue(
            $this->checkForSuccess($result),
            'Could not create context for media source regression: ' . $result->getMessage()
        );

        $bindings = $this->modx->getCollection(modMediaSourceElement::class, [
            'object' => $tv->get('id'),
            'object_class' => modTemplateVar::class,
            'context_key' => $this->contextKey,
        ]);
        $this->assertCount(1, $bindings, 'Expected exactly one media source binding for the new context.');

        /** @var modMediaSourceElement $created */
        $created = reset($bindings);
        $this->assertSame(
            $defaultSourceId,
            (int)$created->get('source'),
            'New context binding must use default_media_source, not a custom source.'
        );
        $this->assertNotEquals(
            (int)$customSource->get('id'),
            (int)$created->get('source'),
            'Custom source from existing contexts must not be copied on context create.'
        );
    }
}
