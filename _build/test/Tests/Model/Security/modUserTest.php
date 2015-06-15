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
 * Tests related to the modUser class.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group User
 * @group modUser
 */
class modUserTest extends MODxTestCase {
    /** @var modUser $user */
    public $user;

    public function setUp() {
        parent::setUp();
        $this->user = $this->modx->newObject('modUser');
        $this->user->fromArray(array(
            'id' => 123456,
            'username' => 'unit-test-user',
            'password' => md5('boogles'),
            'cachepwd' => '',
            'class_key' => 'modUser',
            'active' => true,
            'remote_key' => '',
            'remote_data' => array(),
            'hash_class' => 'hashing.modMD5',
            'salt' => '',
            'primary_group' => 1,
        ),'',true,true);
    }

    /**
     * Test the overrides on xPDOObject::set for the user
     * 
     * @param string $field
     * @param mixed $value
     * @param mixed $expected
     * @dataProvider providerSet
     */
    public function testSet($field,$value,$expected) {
        $this->user->set($field,$value);
        $actual = $this->user->get($field);
        $this->assertEquals($expected,$actual);
    }
    /**
     * @return array
     */
    public function providerSet() {
        return array(
            array('password','boogie',md5('boogie')),
            array('cachepwd','boogie',md5('boogie')),
        );
    }

    /**
     * Ensure generateToken returns a non-empty value
     * @return void
     */
    public function testGenerateToken() {
        $token = $this->user->generateToken('');
        $this->assertNotEmpty($token);
    }

    /**
     *
     * @param int $length
     * @param array $options
     * @dataProvider providerGeneratePassword
     */
    public function testGeneratePassword($length,array $options = array()) {
        $password = $this->user->generatePassword($length,$options);
        $this->assertNotEmpty($password);
        $this->assertEquals($length,strlen($password));
    }
    /**
     * @return array
     */
    public function providerGeneratePassword() {
        return array(
            array(10),
            array(1),
        );
    }

    /**
     * Ensure passwordMatches works
     * @return void
     */
    public function testPasswordMatches() {
        $this->assertTrue($this->user->passwordMatches('boogles'));
        $this->assertFalse($this->user->passwordMatches('bugles'));
    }
}
