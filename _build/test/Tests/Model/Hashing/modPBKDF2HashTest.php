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
