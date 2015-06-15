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
 * Tests related to the modRegistry class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Registry
 * @group modRegistry
 */
class modRegistryTest extends MODxTestCase {
    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();
        $modx =& MODxTestHarness::getFixture('modX', 'modx');
        $modx->getService('registry', 'registry.modRegistry');
    }

    /**
     * Test the modRegistry->getRegister() method.
     *
     * @dataProvider providerGetRegister
     * @param boolean $shouldPass Indicates if the test is expected to pass.
     * @param string $name The name or key of the register to get.
     * @param string $class The name of the modRegister class implementation.
     * @param array $options An array of options for the modRegister instance to use if not already created.
     */
    public function testGetRegister($shouldPass, $name, $class, $options) {
        $this->modx->registry->getRegister($name, $class, $options);
        $actualClass = $this->modx->loadClass($class);
        if ($actualClass === false) {
            $actualClass = 'modRegister';
        }
        if ($shouldPass) {
            $this->assertInstanceOf($actualClass, $this->modx->registry->$name, "Could not get a valid modRegister instance.");
        } else {
            $this->assertNotInstanceOf($actualClass, $this->modx->registry->$name, "Got an unexpected modRegister instance.");
        }
    }
    public function providerGetRegister() {
        return array(
            array(true, 'testFileRegister', 'registry.modFileRegister', array('directory' => 'testFileRegister')),
            array(true, 'testFileRegister2', 'registry.modFileRegister', array('directory' => 'testFileRegister2')),
            array(true, 'testDbRegister', 'registry.modDbRegister', array('directory' => 'testDbRegister')),
            array(true, 'testDbRegister2', 'registry.modDbRegister', array('directory' => 'testDbRegister2')),
            array(false, 'testDbRegister3', 'registry.modDbRegister3', array('directory' => 'testDbRegister3')),
            array(false, 'modx', 'registry.modFileRegister', array('directory' => 'modx')),
        );
    }

    /**
     * Test the modRegistry->addRegister() method.
     *
     * @dataProvider providerAddRegister
     * @param boolean $shouldPass Indicates if the test is expected to pass.
     * @param string $name The name or key of the register to add.
     * @param string $class The name of the modRegister class implementation.
     * @param array $options An array of options for the modRegister instance to use if not already created.
     */
    public function testAddRegister($shouldPass, $name, $class, $options) {
        $this->modx->registry->addRegister($name, $class, $options);
        $actualClass = $this->modx->loadClass($class);
        if ($actualClass === false) {
            $actualClass = 'modRegister';
        }
        if ($shouldPass) {
            $this->assertInstanceOf($actualClass, $this->modx->registry->$name, "Could not get a valid modRegister instance.");
        } else {
            $this->assertNotInstanceOf($actualClass, $this->modx->registry->$name, "Got an unexpected modRegister instance.");
        }
    }
    public function providerAddRegister() {
        return array(
            array(true, 'testFileRegister', 'registry.modFileRegister', array('directory' => 'testFileRegister')),
            array(true, 'testFileRegister2', 'registry.modFileRegister', array('directory' => 'testFileRegister2')),
            array(true, 'testDbRegister', 'registry.modDbRegister', array('directory' => 'testDbRegister')),
            array(true, 'testDbRegister2', 'registry.modDbRegister', array('directory' => 'testDbRegister2')),
            array(false, 'testDbRegister3', 'registry.modDbRegister3', array('directory' => 'testDbRegister3')),
            array(false, 'modx', 'registry.modFileRegister', array('directory' => 'modx')),
        );
    }

    /**
     * Test modRegistry->removeRegister() method.
     *
     * @dataProvider providerRemoveRegister
     * @param string $name The name or key of the register instance to remove.
     */
    public function testRemoveRegister($name) {
        $this->modx->registry->removeRegister($name);
        $this->assertNotInstanceOf('modRegister', $this->modx->registry->$name, "Could not remove register with key {$name}");
    }
    public function providerRemoveRegister() {
        return array(
            array('testFileRegister'),
            array('testFileRegister2'),
            array('testDbRegister'),
            array('testDbRegister2'),
            array('testDbRegister3'),
            array('modx'),
        );
    }

    public function testSetLogging() {
        $this->assertTrue(true);
    }

    public function testResetLogging() {
        $this->assertTrue(true);
    }

    public function testIsLogging() {
        $this->assertTrue(true);
    }
}
