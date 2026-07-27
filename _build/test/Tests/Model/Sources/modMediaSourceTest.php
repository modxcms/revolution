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


use MODX\Revolution\MODxTestCase;
use MODX\Revolution\modContext;
use MODX\Revolution\Sources\modFileMediaSource;
use MODX\Revolution\Sources\modMediaSource;

/**
 * Tests related to the modMediaSource class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Sources
 * @group modMediaSource
 */
class modMediaSourceTest extends MODxTestCase {
    /** @var modMediaSource $source */
    public $source;

    /**
     * Setup fixtures before each test.
     *
     * @before
     */
    public function setUpFixtures() {
        parent::setUpFixtures();

        $this->source = $this->modx->newObject(modMediaSource::class);
        $this->source->fromArray([
            'name' => 'UnitTestSource',
            'description' => '',
            'class_key' => modFileMediaSource::class,
            'properties' => [],
        ],'',true);
    }
    /**
     * Tear down fixtures after each test.
     *
     * @after
     */
    public function tearDownFixtures() {
        parent::tearDownFixtures();
        $this->source = null;
    }

    public function testExample() {
        $this->assertTrue(true);
    }

    /**
     * Media Source policies stay mgr-scoped; findPolicy must not load the mgr Context
     * object from a non-mgr request (avoids anonymous INFO ACL noise, #16212).
     */
    public function testFindPolicyKeepsMgrScopeWithoutLoadingMgrContext()
    {
        $this->source->save();
        unset($this->modx->contexts['mgr']);

        $web = $this->modx->getObject(modContext::class, ['key' => 'web']);
        $this->assertNotEmpty($web, 'web context fixture required');
        $previous = $this->modx->context;
        $this->modx->context = $web;

        try {
            $this->source->findPolicy('web');
            $this->assertArrayNotHasKey(
                'mgr',
                $this->modx->contexts,
                'findPolicy must not call getContext(mgr) on the frontend'
            );
        } finally {
            $this->modx->context = $previous;
            $this->source->remove();
        }
    }
}
