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
 * Tests related to the modHashing class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Hashing
 * @group modHashing
 */
class modHashingTest extends MODxTestCase {
    /**
     * Test the getOption method.
     *
     * @dataProvider providerGetOption
     * @param $key The option key
     * @param $options Local options to be passed to the method
     * @param $hashingOptions Options set for the modHashing instance
     * @param $modxOptions Options set in MODX
     * @param $expected The expected value
     */
    public function testGetOption($key, $options, $hashingOptions, $modxOptions, $expected) {
        foreach ($modxOptions as $moKey => $moValue) $this->modx->setOption($moKey, $moValue);
        $this->modx->getService('hashing', 'hashing.modHashing', '', $hashingOptions);
        $actual = $this->modx->hashing->getOption($key, $options);
        unset($this->modx->services['hashing']);
        foreach ($modxOptions as $moKey => $moValue) unset($this->modx->config[$moKey]);
        $this->assertEquals($expected, $actual, "Did not get the expected option value.");
    }
    public function providerGetOption() {
        return array(
            array('option1', array(), array(), array('hashing_option1' => 'modx'), 'modx'),
            array('option2', array(), array('option2' => 'hashing'), array(), 'hashing'),
            array('option3', array('option3' => 'local'), array(), array(), 'local'),
            array('option4', array('option4' => 'local'), array('option4' => 'hashing'), array('hashing_option4' => 'modx'), 'local'),
            array('option5', array('option99' => 'local'), array('option5' => 'hashing'), array('hashing_option5' => 'modx'), 'hashing'),
            array('option6', null, null, array('hashing_option6' => 'modx'), 'modx'),
            array('option7', array(), null, array('hashing_option7' => 'modx'), 'modx'),
            array('option8', null, null, array(), null),
            array('option8', array(), null, array(), null),
            array('option8', null, array(), array(), null),
        );
    }
}
