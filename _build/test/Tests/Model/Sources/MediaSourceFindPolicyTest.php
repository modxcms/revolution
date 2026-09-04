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

namespace MODX\Revolution\Tests\Model\Sources;

use MODX\Revolution\modContext;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Sources\modFileMediaSource;
use MODX\Revolution\Sources\modMediaSource;

/**
 * findPolicy() must keep Media Source ACLs mgr-scoped without loading mgr Context (#16212).
 *
 * @group Model
 * @group Sources
 * @group modMediaSource
 */
class MediaSourceFindPolicyTest extends MODxTestCase
{
    public function testFindPolicyKeepsMgrScopeWithoutLoadingMgrContext()
    {
        /** @var modMediaSource $source */
        $source = $this->modx->newObject(modMediaSource::class);
        $source->fromArray([
            'name' => 'UnitTestFindPolicySource',
            'description' => '',
            'class_key' => modFileMediaSource::class,
            'properties' => [],
        ], '', true);
        $this->assertTrue((bool)$source->save());

        unset($this->modx->contexts['mgr']);

        $web = $this->modx->getObject(modContext::class, ['key' => 'web']);
        $this->assertNotEmpty($web, 'web context fixture required');
        $previous = $this->modx->context;
        $this->modx->context = $web;

        try {
            $source->findPolicy('web');
            $this->assertArrayNotHasKey(
                'mgr',
                $this->modx->contexts,
                'findPolicy must not call getContext(mgr) on the frontend'
            );
        } finally {
            $this->modx->context = $previous;
            $source->remove();
        }
    }
}
