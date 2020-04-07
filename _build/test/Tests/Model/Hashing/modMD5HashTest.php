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


use MODX\Revolution\Hashing\modMD5;
use MODX\Revolution\MODxTestCase;

/**
 * Tests related to the modMD5 class, a derivative of modHash.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Hashing
 * @group modHash
 * @group modHashing
 */
class modMD5HashTest extends MODxTestCase {
    /**
     * Test the modMD5->hash() method.
     *
     * @dataProvider providerHash
     * @param $string The string to create a hash of.
     * @param $options The options for the hash process.
     * @param $expected The expected hash value.
     */
    public function testHash($string, $options, $expected) {
        $this->modx->getService('hashing', 'hashing.modHashing');
        /** @var modMD5 $md5 */
        $md5 = $this->modx->hashing->getHash('md5', 'hashing.modMD5', $options);
        $actual = $md5->hash($string);
        $this->assertEquals($expected, $actual, "Expected hash value not generated.");
    }
    public function providerHash() {
        return [
            ['password', [], md5('password')],
            ['password', null, md5('password')],
            ['what do you think of this?', [], md5('what do you think of this?')],
            ['what do you think of this?', null, md5('what do you think of this?')],
        ];
    }
}
