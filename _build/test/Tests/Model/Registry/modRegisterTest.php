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
        $modx =& MODxTestHarness::getFixture('modX', 'modx');
        $modx->getService('registry', 'registry.modRegistry');
        $modx->loadClass('registry.modRegister', '', false, true);
        include_once dirname(__FILE__) . '/modmemoryregister.mock.php';
        $modx->registry->addRegister('register', 'modMemoryRegister', array('directory' => 'register'));
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
        return array(
            array('/food'),
            array('/food/'),
            array('/beer/'),
            array('/beer'),
            array('/food/beer/'),
        );
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
        return array(
            array('/', ''),
            array('/food/', 'food'),
            array('/beer/', '/beer'),
            array('/food/beer/', '/food/beer/'),
        );
    }
}
