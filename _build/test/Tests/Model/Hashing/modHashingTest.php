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
namespace MODX\Revolution\Tests\Model\Hashing;


use MODX\Revolution\MODxTestCase;

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
