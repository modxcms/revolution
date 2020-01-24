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
namespace MODX\Revolution\Tests\Model\Security;


use MODX\Revolution\modUser;
use MODX\Revolution\MODxTestCase;

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
        $this->user = $this->modx->newObject(modUser::class);
        $this->user->fromArray([
            'id' => 123456,
            'username' => 'unit-test-user',
            'password' => md5('boogles'),
            'cachepwd' => '',
            'class_key' => modUser::class,
            'active' => true,
            'remote_key' => '',
            'remote_data' => [],
            'hash_class' => 'hashing.modMD5',
            'salt' => '',
            'primary_group' => 1,
        ],'',true,true);
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
        return [
            ['password','boogie',md5('boogie')],
            ['cachepwd','boogie',md5('boogie')],
        ];
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
    public function testGeneratePassword($length,array $options = []) {
        $password = $this->user->generatePassword($length,$options);
        $this->assertNotEmpty($password);
        $this->assertEquals($length,strlen($password));
    }
    /**
     * @return array
     */
    public function providerGeneratePassword() {
        return [
            [10],
            [8],
        ];
    }

    public function testGeneratePasswordWithPasswordLengthOption()
    {
        $password = $this->user->generatePassword();
        $this->assertNotEmpty($password);
        $this->assertNotEquals(12, strlen($password));

        $this->modx->setOption('password_generated_length',12);
        $anotherPassword = $this->user->generatePassword();
        $this->assertNotEmpty($anotherPassword);
        $this->assertEquals(12, strlen($anotherPassword));

        $this->modx->setOption('password_generated_length','');
        $yetAnotherPassword = $this->user->generatePassword();
        $this->assertNotEmpty($yetAnotherPassword);
        $this->assertEquals(10, strlen($yetAnotherPassword));
    }

    public function testGeneratePasswordMinLength()
    {
        $defaultPasswordMinLength = $this->modx->getOption('password_min_length', 8);
        $password = $this->user->generatePassword();
        $this->assertGreaterThanOrEqual($defaultPasswordMinLength, strlen($password));

        $passwordGeneratedLength = 10;
        $passwordMinLength = 12;
        $this->modx->setOption('password_generated_length', $passwordGeneratedLength);
        $this->modx->setOption('password_min_length', $passwordMinLength);
        $anotherPassword = $this->user->generatePassword();
        $this->assertGreaterThanOrEqual($passwordMinLength, strlen($anotherPassword));
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
