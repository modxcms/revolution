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
        $hasher = $this->modx->hashing->getHash('modNative', 'hashing.modNative');

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
