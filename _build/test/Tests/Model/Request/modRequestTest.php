<?php
/**
 * MODX Revolution
 *
 * Copyright 2006-2011 by MODX, LLC.
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
 * Tests related to the modRequest class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Request
 * @group modRequest
 */
class modRequestTest extends MODxTestCase {
    /** @var modRequest $request */
    public $request;

    public function setUp() {
        parent::setUp();
        /** @var modNamespace $namespace */
        $namespace = $this->modx->newObject('modNamespace');
        $namespace->set('name','unit-test');
        $namespace->set('path','{core_path}');
        $namespace->save();

        /** @var modAction $action */
        $action = $this->modx->newObject('modAction');
        $action->fromArray(array(
            'namespace' => 'unit-test',
            'parent' => 0,
            'controller' => 'index',
            'haslayout' => 1,
            'lang_topics' => '',
        ));
        $action->save();

        $this->modx->loadClass('modRequest',null,true,true);
        $this->request = new modRequest($this->modx);
    }

    public function tearDown() {
        parent::tearDown();

        /** @var modNamespace $namespace */
        $namespace = $this->modx->getObject('modNamespace',array('name' => 'unit-test'));
        if ($namespace) { $namespace->remove(); }

        $actions = $this->modx->getCollection('modAction',array(
            'namespace' => 'unit-test',
        ));
        /** @var modAction $action */
        foreach ($actions as $action) {
            $action->remove();
        }
    }

    /**
     * Test the getAllActionIDs method
     */
    public function testGetAllActionIDs() {
        $actions = $this->request->getAllActionIDs();
        $total = $this->modx->getCount('modAction');
        $this->assertTrue(count($actions) == $total,'The getAllActionIDs method did not get all of the Actions that exist.');

        $actions = $this->request->getAllActionIDs('unit-test');
        $total = $this->modx->getCount('modAction',array('namespace' => 'unit-test'));
        $this->assertTrue(count($actions) == $total,'The getAllActionIDs method did not filter down by namespace when grabbing actions.');
    }
}