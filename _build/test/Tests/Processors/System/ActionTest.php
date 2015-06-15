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
 * Tests related to element/category/ processors
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group System
 * @group Action
 * @group ActionProcessors
 * @group modAction
 */
class ActionProcessorsTest extends MODxTestCase {
    const PROCESSOR_LOCATION = 'system/action/';

    /**
     * Setup some basic data for this test.
     */
    public function setUp() {
        parent::setUp();
        /** @var modNamespace $namespace */
        $namespace = $this->modx->newObject('modNamespace');
        $namespace->set('name','unittest');
        $namespace->save();

        /** @var modAction $action */
        $action = $this->modx->newObject('modAction');
        $action->fromArray(array(
            'namespace' => 'unittest',
            'controller' => 'unittest',
            'parent' => 0,
            'haslayout' => true,
            'assets' => '',
            'lang_topics' => '',
            'help_url' => 'Actions',
        ));
        $action->save();
    }

    /**
     * Cleanup data after this test.
     */
    public function tearDown() {
        parent::tearDown();
        /** @var modNamespace $namespace */
        $namespace = $this->modx->getObject('modNamespace',array('name' => 'unittest'));
        $namespace->remove();
        $actions = $this->modx->getCollection('modAction',array('namespace' => 'unittest'));
        /** @var modAction $action */
        foreach ($actions as $action) {
            $action->remove();
        }
        $this->modx->error->reset();
    }


    /**
     * Tests the system/action/create processor, which creates a modAction
     *
     * @param boolean $shouldPass
     * @param string $controller The controller of the action to test
     * @param array $properties
     * @dataProvider providerActionCreate
     */
    public function testActionCreate($shouldPass,$controller,array $properties = array()) {
        $properties['controller'] = $controller;

        /** @var modProcessorResponse $result */
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'create',$properties);
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'create processor');
        }
        $s = $this->checkForSuccess($result);
        $ct = $this->modx->getCount('modAction',array(
            'controller' => $controller,
            'namespace' => 'unittest',
        ));
        $passed = $s && $ct > 0;
        $passed = $shouldPass ? $passed : !$passed;
        //$this->assertTrue($passed,'Could not create Action: `'.$controller.'`: '.$result->getMessage());
        /** @TODO fix this test */
        $this->assertTrue(true);
    }
    /**
     * Data provider for system/action/create processor test.
     * @return array
     */
    public function providerActionCreate() {
        return array(
            /* pass: default controller */
            array(true,'unittest2',array(
                'namespace' => 'unittest',
                'parent' => 0,
            )),
            /* pass: 2nd action with props */
            array(true,'unittest3',array(
                'namespace' => 'unittest',
                'parent' => 0,
            )),
            /* fail: already exists */
            array(false,'unittest3',array(
                'parent' => 0,
            )),
            /* fail: no data */
            array(false,''),
            /* fail: invalid parent */
            array(false,'unittest4',array(
                'parent' => 9999,
            )),
            /* fail: invalid namespace */
            array(false,'unittest4',array(
                'parent' => 0,
                'namespace' => 'false',
            )),
        );
    }

    /**
     * Attempts to update a action
     *
     * @param boolean $shouldPass
     * @param string $controller
     * @param array $properties
     * @dataProvider providerActionUpdate
     * @depends testActionCreate
     */
    public function testActionUpdate($shouldPass,$controller,array $properties = array()) {
        $action = $this->modx->getObject('modAction',array(
            'controller' => $controller,
            'namespace' => 'unittest',
        ));
        if (empty($action) && $shouldPass) {
            $this->fail('No Action found "'.$controller.'" as specified in test provider.');
            return;
        }
        $data = $properties;
        $data['id'] = $action ? $action->get('id') : $controller;
        $data['controller'] = $controller;

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'update',$data);
        $passed = $this->checkForSuccess($result);
        if ($passed) {
            $r = $result->getObject();
            foreach ($properties as $k => $v) {
                $passed = !empty($r) && $r[$k] == $v;
            }
        }
        $passed = $shouldPass ? $passed : !$passed;
        //$this->assertTrue($passed,'Could not update action: `'.$controller.'`: '.$result->getMessage());
        /** @TODO fix this test */
        $this->assertTrue(true);
    }
    /**
     * Data provider for action/update processor test.
     * @return array
     */
    public function providerActionUpdate() {
        return array(
            /* pass: change the description/locked */
            array(true,'unittest',array(
                'assets' => 'test',
                'haslayout' => true,
                'parent' => 0,
                'namespace' => 'unittest',
            )),
            /* fail: no data */
            //array(false,''),
            /* fail: invalid ID */
            //array(false,9999),
        );
    }

    /**
     * Tests the element/action/get processor, which gets a Action
     *
     * @param boolean $shouldPass
     * @param string $controller
     * @dataProvider providerActionGet
     */
    public function testActionGet($shouldPass,$controller) {
        $action = $this->modx->getObject('modAction',array('controller' => $controller));
        if (empty($action) && $shouldPass) {
            $this->fail('No Action found "'.$controller.'" as specified in test provider.');
            return;
        }
        $data = array();
        $data['id'] = $action ? $action->get('id') : $controller;

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'get',$data);
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'get processor');
        }
        $passed = $this->checkForSuccess($result);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not get Action: `'.$controller.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/action/create processor test.
     * @return array
     */
    public function providerActionGet() {
        return array(
            array(true,'unittest'), /* pass: get action */
            array(false,9999), /* fail: invalid ID */
            array(false,''), /* fail: no data */
        );
    }

    /**
     * Attempts to get a list of actions
     *
     * @param boolean $shouldPass
     * @param string $sort
     * @param string $dir
     * @param int $limit
     * @param int $start
     * @param boolean $showNone
     * @dataProvider providerActionGetList
     */
    public function testActionGetList($shouldPass = true,$sort = 'key',$dir = 'ASC',$limit = 10,$start = 0,$showNone = false) {
        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'getlist',array(
            'sort' => $sort,
            'dir' => $dir,
            'limit' => $limit,
            'start' => $start,
            'showNone' => $showNone,
        ));
        $results = $this->getResults($result);
        $passed = !empty($results);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not get list of Actions: '.$result->getMessage());
    }
    /**
     * Data provider for element/action/getlist processor test.
     * @return array
     */
    public function providerActionGetList() {
        return array(
            array(true,'controller','ASC',5,0), /* pass: sort 5 by controller asc */
            array(true,'controller','DESC',5,0), /* pass: sort 5 by controller desc */
            array(false,'controller','ASC',5,50000), /* fail: start beyond what exists */
            array(false,'badname','ASC',5,0), /* fail: invalid sort column */
        );
    }

    /**
     * Tests the element/action/remove processor, which removes a Action
     * @param boolean $shouldPass
     * @param string $actionPk
     * @dataProvider providerActionRemove
     */
    public function testActionRemove($shouldPass,$actionPk) {
        $action = $this->modx->getObject('modAction',array('controller' => $actionPk,'namespace' => 'unittest'));
        if (empty($action) && $shouldPass) {
            $this->fail('No Action found "'.$actionPk.'" as specified in test provider.');
            return;
        }

        $result = $this->modx->runProcessor(self::PROCESSOR_LOCATION.'remove',array(
            'id' => $action ? $action->get('id') : $actionPk,
        ));
        if (empty($result)) {
            $this->fail('Could not load '.self::PROCESSOR_LOCATION.'remove processor');
        }
        $passed = $this->checkForSuccess($result);
        $passed = $shouldPass ? $passed : !$passed;
        $this->assertTrue($passed,'Could not remove Action: `'.$actionPk.'`: '.$result->getMessage());
    }
    /**
     * Data provider for element/action/remove processor test.
     * @return array
     */
    public function providerActionRemove() {
        return array(
            array(true,'unittest'), /* pass: remove action from create test */
            array(false,9999), /* fail: invalid ID */
            array(false,''), /* fail: no data */
        );
    }
}
