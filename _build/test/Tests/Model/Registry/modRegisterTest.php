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
namespace MODX\Revolution\Tests\Model\Registry;


use MODX\Revolution\modX;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\MODxTestHarness;

/**
 * Tests related to the modRegister class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Registry
 * @group modRegister
 */
class modRegisterTest extends MODxTestCase {
    public static function setUpBeforeClass() {
        /** @var modX $modx */
        $modx =& MODxTestHarness::getFixture(modX::class, 'modx');
        $modx->getService('registry', 'registry.modRegistry');
        $modx->registry->addRegister('register', modMemoryRegister::class, ['directory' => 'register']);
    }

    public static function tearDownAfterClass() {
        /** @var modX $modx */
        $modx =& MODxTestHarness::getFixture(modX::class, 'modx');
        $modx->getService('registry', 'registry.modRegistry');
        $modx->registry->removeRegister('register');
    }

    public function testGetKey() {
        $this->assertTrue($this->modx->registry->register->getKey() === 'register', 'Could not get valid key from register.');
    }

    public function testConnect() {
        $this->assertTrue($this->modx->registry->register->connect(), 'Could not connect to register');
    }

    /**
     * Test modRegister->subscribe() method.
     *
     * @dataProvider providerSubscribe
     * @param $topic
     */
    public function testSubscribe($topic) {
        $this->modx->registry->register->subscribe($topic);
        $this->assertTrue(in_array($topic, $this->modx->registry->register->subscriptions), "Could not subscribe to register topic {$topic}");
    }
    public function providerSubscribe() {
        return [
            ['/food'],
            ['/food/'],
            ['/beer/'],
            ['/beer'],
            ['/food/beer/'],
        ];
    }

    /**
     * Test modRegister->setCurrentTopic() method.
     *
     * @dataProvider providerSetCurrentTopic
     * @param string $expected The expected currentTopic result.
     * @param string $topic The topic string to pass.
     */
    public function testSetCurrentTopic($expected, $topic) {
        $this->modx->registry->register->setCurrentTopic($topic);
        $this->assertEquals($expected, $this->modx->registry->register->getCurrentTopic(), "Could not set current topic.");
    }
    public function providerSetCurrentTopic() {
        return [
            ['/', ''],
            ['/food/', 'food'],
            ['/beer/', '/beer'],
            ['/food/beer/', '/food/beer/'],
        ];
    }
}
