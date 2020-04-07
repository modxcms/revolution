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

    public function setUp() {
        parent::setUp();

        $this->source = $this->modx->newObject(modMediaSource::class);
        $this->source->fromArray([
            'name' => 'UnitTestSource',
            'description' => '',
            'class_key' => modFileMediaSource::class,
            'properties' => [],
        ],'',true);
    }
    public function tearDown() {
        parent::tearDown();
        $this->source = null;
    }

    public function testExample() {
        $this->assertTrue(true);
    }
}
