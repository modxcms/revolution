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


use MODX\Revolution\Hashing\modNative;
use MODX\Revolution\MODxTestCase;

/**
 * Tests related to the modNative class, a derivative of modHash.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Hashing
 * @group modHash
 * @group modHashing
 */
class modNativeHashTest extends MODxTestCase {
    /**
     * Test the native hasher implementation
     *
     * @dataProvider providerHash
     * @param $string The string to create a hash of.
     * @param $options The options for the hash process.
     * @param $expected The expected hash value.
     */
    public function testHash($string) {
        $this->modx->getService('hashing', 'hashing.modHashing');
        /** @var modNative $hasher */
        $hasher = $this->modx->hashing->getHash(modNative::class, 'hashing.modNative');

        $generated = $hasher->hash($string);
        $this->assertNotEmpty($generated);
        $this->assertTrue($hasher->verify($string, $generated));
        $this->assertFalse($hasher->verify($string . 'X', $generated));
        $this->assertFalse($hasher->verify($string, $generated . '$'));
    }

    public function providerHash() {
        return array(
            array('password123'),
            array('123456'),
            array('what do you think of this?'),
            array('letmein'),
        );
    }
}
