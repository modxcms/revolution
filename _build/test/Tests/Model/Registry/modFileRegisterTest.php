<?php
/**
 * MODX Revolution
 *
 * Copyright 2006-2014 by MODX, LLC.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package modx-test
 */
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
        $modx =& MODxTestHarness::getFixture('modX', 'modx');
        $modx->getService('registry', 'registry.modRegistry');
        $modx->registry->addRegister('register', 'registry.modFileRegister', array('directory' => 'register'));
    }

    public static function tearDownAfterClass() {
        /** @var modX $modx */
        $modx =& MODxTestHarness::getFixture('modX', 'modx');
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
        return array(
            array('/food'),
            array('/food/'),
            array('/beer/'),
            array('/beer'),
            array('/food/beer/'),
        );
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
        return array(
            array('/', ''),
            array('/food/', 'food'),
            array('/beer/', '/beer'),
            array('/food/beer/', '/food/beer/'),
        );
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
        return array(
            array(
                true,
                '/topic1/',
                '1',
                array()
            ),
            array(
                true,
                '/topic2/',
                array('1', '2', '3'),
                array()
            ),
            array(
                true,
                '/topic3/',
                array('a' => '1', 'b' => '2', 'c' => '3'),
                array()
            ),
            array(
                true,
                '/topic4/',
                array('a' => 1, 'b' => 2.0, 'c' => 3.25, 'd' => 4.1390, 'e' => 5),
                array()
            ),
        );
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
        return array(
            array(
                array('1'),
                '/topic1/',
                array(
                    'poll_limit' => 1,
                )
            ),
            array(
                array('1', '2', '3'),
                '/topic2/',
                array(
                    'poll_limit' => 1,
                )
            ),
            array(
                array('1', '2', '3'),
                '/topic3/',
                array(
                    'poll_limit' => 1,
                    'remove_read' => false,
                )
            ),
            array(
                array('1'),
                '/topic3/a',
                array(
                    'poll_limit' => 1,
                )
            ),
            array(
                array('2'),
                '/topic3/b',
                array(
                    'poll_limit' => 1,
                )
            ),
            array(
                array('3'),
                '/topic3/c',
                array(
                    'poll_limit' => 1,
                )
            ),
            array(
                array('a' => 1, 'b' => 2.0, 'c' => 3.25, 'd' => 4.1390, 'e' => 5),
                '/topic4/',
                array(
                    'poll_limit' => 1,
                    'remove_read' => false,
                    'include_keys' => true
                )
            ),
            array(
                array(1, 2.0, 3.25, 4.1390, 5),
                '/topic4/',
                array(
                    'poll_limit' => 1,
                )
            ),
        );
    }
}
