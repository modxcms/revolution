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
     * @return void
     */
    public function setUp() {
        parent::setUp();

        $this->modx->loadClass('sources.modMediaSource');
        $this->source = $this->modx->newObject('sources.modMediaSource');
        $this->source->fromArray(array(
            'name' => 'UnitTestSource',
            'description' => '',
            'class_key' => 'sources.modFileMediaSource',
            'properties' => array(),
        ),'',true);
    }
    public function tearDown() {
        parent::tearDown();
        $this->source = null;
    }

    public function testExample() {
        $this->assertTrue(true);
    }
}
