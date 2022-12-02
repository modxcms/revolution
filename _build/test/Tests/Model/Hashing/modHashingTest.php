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


use MODX\Revolution\Hashing\modHashing;
use MODX\Revolution\Hashing\modMD5;
use MODX\Revolution\Hashing\modNative;
use MODX\Revolution\Hashing\modPBKDF2;
use MODX\Revolution\modParser;
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
        return [
            ['option1', [], [], ['hashing_option1' => 'modx'], 'modx'],
            ['option2', [], ['option2' => 'hashing'], [], 'hashing'],
            ['option3', ['option3' => 'local'], [], [], 'local'],
            ['option4', ['option4' => 'local'], ['option4' => 'hashing'], ['hashing_option4' => 'modx'], 'local'],
            ['option5', ['option99' => 'local'], ['option5' => 'hashing'], ['hashing_option5' => 'modx'], 'hashing'],
            ['option6', null, null, ['hashing_option6' => 'modx'], 'modx'],
            ['option7', [], null, ['hashing_option7' => 'modx'], 'modx'],
            ['option8', null, null, [], null],
            ['option8', [], null, [], null],
            ['option8', null, [], [], null],
        ];
    }

    /**
     * @dataProvider providerGetHashInstance
     * @param $key
     * @param $class
     * @param $expected
     * @return void
     */
    public function testGetHashInstance($key, $class, $expected)
    {
        /** @var modHashing $modHashing */
        $modHashing = $this->modx->getService('hashing', 'hashing.modHashing');

        $hasher = $modHashing->getHash($key, $class);
        if ($expected === null) {
            $this->assertNull($hasher);
        }
        else {
            $this->assertInstanceOf($expected, $hasher);
        }
    }

    public function providerGetHashInstance()
    {
        return [
            ['hasher-md5', modMD5::class, modMD5::class],
            ['hasher-native', modNative::class, modNative::class],
            ['hasher-pbkdf2', modPBKDF2::class, modPBKDF2::class],
            ['hasher-null', modParser::class, null], // Not a hasher class should return null
        ];
    }
}
