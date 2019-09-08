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


use MODX\Revolution\Hashing\modPBKDF2;
use MODX\Revolution\MODxTestCase;

/**
 * Tests related to the modPBKDF2 class, a derivative of modHash.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Hashing
 * @group modHash
 * @group modHashing
 */
class modPBKDF2HashTest extends MODxTestCase {
    /**
     * Test the modPBKDF2->hash() method.
     *
     * @dataProvider providerHash
     * @param $string The string to create a hash of.
     * @param $options The options for the hash process.
     * @param $expected The expected hash value.
     */
    public function testHash($string, $options, $expected) {
        $this->modx->getService('hashing', 'hashing.modHashing');
        /** @var modPBKDF2 $pbkdf2 */
        $pbkdf2 = $this->modx->hashing->getHash('pbkdf2', 'hashing.modPBKDF2');
        $actual = $pbkdf2->hash($string, $options);
        $this->assertEquals($expected, $actual, "Expected hash value not generated.");
    }
    public function providerHash() {
        return array(
            array('password', array('salt' => '123'), 'VCIGwMm0t9bKMa8xeOKMG6BZ0wNadGAikev95fnnkkQ='),
            array('password', array('salt' => 'abc'), 'e/+7RSuHvTHideDuZkpvFXtq65+oHM9xONAsEVJaV6s='),
            array('what do you think of this?', array('salt' => 'abc123'), 'Pgf+nVGBXjkX6kqWsSYvyn8kWsf0jeArBJW8V50wUoE='),
            array('what do you think of this?', array('salt' => '123abc'), 'zh7hBkVxmK9JZ8RKqRJCrLr9wkRn48O+g0igHi+H2WY='),
        );
    }
}
