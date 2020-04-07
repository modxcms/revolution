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
 * Tests related to the modFileRegister class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Registry
 * @group modRegister
 * @group modFileRegister
 */
class modFileRegisterTest extends MODxTestCase {
    public static function setUpBeforeClass() {
        /** @var modX $modx */
        $modx =& MODxTestHarness::getFixture(modX::class, 'modx');
        $modx->getService('registry', 'registry.modRegistry');
        $modx->registry->addRegister('register', 'registry.modFileRegister', ['directory' => 'register']);
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
     * Test modFileRegister->subscribe() method.
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
     * Test modFileRegister->setCurrentTopic() method.
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

    /**
     * Test modFileRegister->send() method.
     *
     * @dataProvider providerSend
     * @param $expected
     * @param $topic
     * @param $msg
     * @param $options
     */
    public function testSend($expected, $topic, $msg, $options) {
        $this->modx->registry->register->subscribe($topic);
        $actual = $this->modx->registry->register->send($topic, $msg, $options);
        $this->modx->registry->register->unsubscribe($topic);
        $this->assertEquals($expected, $actual, "Could not send msg(s) to the topic.");
    }
    public function providerSend() {
        return [
            [
                true,
                '/topic1/',
                '1',
                []
            ],
            [
                true,
                '/topic2/',
                ['1', '2', '3'],
                []
            ],
            [
                true,
                '/topic3/',
                ['a' => '1', 'b' => '2', 'c' => '3'],
                []
            ],
            [
                true,
                '/topic4/',
                ['a' => 1, 'b' => 2.0, 'c' => 3.25, 'd' => 4.1390, 'e' => 5],
                []
            ],
        ];
    }

    /**
     * Test modFileRegister->read() method.
     *
     * @depends testSend
     * @dataProvider providerRead
     * @param $expected
     * @param $topic
     * @param $options
     */
    public function testRead($expected, $topic, $options) {
        $this->modx->registry->register->subscribe($topic);
        $actual = $this->modx->registry->register->read($options);
        $this->modx->registry->register->unsubscribe($topic);
        $this->assertEquals($expected, $actual, "Could not read msg(s) from topic.");
    }
    public function providerRead() {
        return [
            [
                ['1'],
                '/topic1/',
                [
                    'poll_limit' => 1,
                ]
            ],
            [
                ['1', '2', '3'],
                '/topic2/',
                [
                    'poll_limit' => 1,
                ]
            ],
            [
                ['1', '2', '3'],
                '/topic3/',
                [
                    'poll_limit' => 1,
                    'remove_read' => false,
                ]
            ],
            [
                ['1'],
                '/topic3/a',
                [
                    'poll_limit' => 1,
                ]
            ],
            [
                ['2'],
                '/topic3/b',
                [
                    'poll_limit' => 1,
                ]
            ],
            [
                ['3'],
                '/topic3/c',
                [
                    'poll_limit' => 1,
                ]
            ],
            [
                ['a' => 1, 'b' => 2.0, 'c' => 3.25, 'd' => 4.1390, 'e' => 5],
                '/topic4/',
                [
                    'poll_limit' => 1,
                    'remove_read' => false,
                    'include_keys' => true
                ]
            ],
            [
                [1, 2.0, 3.25, 4.1390, 5],
                '/topic4/',
                [
                    'poll_limit' => 1,
                ]
            ],
        ];
    }
}
