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

namespace MODX\Revolution\Tests\Processors\Security\Profile;

use MODX\Revolution\modUser;
use MODX\Revolution\modUserProfile;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Security\Profile\Update;

/**
 * Tests for Security/Profile/Update processor (username validation and save).
 *
 * @package modx-test
 * @subpackage Processors
 * @group Processors
 * @group Security
 * @group Profile
 */
class UpdateTest extends MODxTestCase
{
    /** @var modUser */
    protected $testUser;

    /** @var modUserProfile */
    protected $testProfile;

    /** @var modUser */
    protected $originalUser;

    /** @var string */
    protected $originalUsername;

    /**
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->originalUser = $this->modx->user;

        $this->testUser = $this->modx->getObject(modUser::class, 1);
        if (!$this->testUser || !$this->testUser->getOne('Profile')) {
            $this->markTestSkipped('Test requires admin user with profile in database');
        }

        $this->originalUsername = $this->testUser->get('username');
        $this->testProfile = $this->testUser->getOne('Profile');
        $this->modx->user = $this->testUser;
    }

    /**
     * @after
     */
    public function tearDownFixtures()
    {
        if ($this->testUser && $this->originalUsername !== null) {
            $this->testUser->set('username', $this->originalUsername);
            $this->testUser->save();
        }
        if ($this->originalUser) {
            $this->modx->user = $this->originalUser;
        }
        $this->modx->error->reset();
        parent::tearDownFixtures();
    }

    /**
     * @param bool $shouldPass
     * @param string $username
     * @dataProvider providerUsernameValidation
     */
    public function testUsernameValidation($shouldPass, $username)
    {
        $result = $this->modx->runProcessor(Update::class, [
            'username' => $username,
            'fullname' => $this->testProfile->get('fullname'),
            'email' => $this->testProfile->get('email'),
            'newpassword' => 'false',
        ]);

        $this->assertNotNull($result, 'Processor did not run');
        $passed = !$result->isError();
        $this->assertSame($shouldPass, $passed, $result->getMessage());
    }

    public function providerUsernameValidation()
    {
        return [
            [false, ''],
            [false, '  '],
            [false, 'user<name'],
            [false, 'user>name'],
            [false, "user'name"],
            [false, 'user;name'],
            [false, 'user"name'],
            [false, 'user(name)'],
            [true, 'valid_username'],
            [true, 'ValidUser123'],
        ];
    }

    /**
     * Rejects username already taken by another user.
     */
    public function testUsernameUniqueness()
    {
        $takenUsername = 'taken_username_' . time();
        $otherUser = $this->modx->newObject(modUser::class);
        $otherUser->fromArray([
            'username' => $takenUsername,
            'password' => $this->testUser->get('password'),
            'cachepwd' => '',
            'class_key' => modUser::class,
            'active' => true,
            'remote_key' => '',
            'remote_data' => [],
            'hash_class' => $this->testUser->get('hash_class'),
            'salt' => $this->testUser->get('salt'),
            'primary_group' => 1,
        ], '', true, true);
        $otherUser->save();

        $profile = $this->modx->newObject(modUserProfile::class);
        $profile->fromArray([
            'internalKey' => $otherUser->get('id'),
            'fullname' => 'Other User',
            'email' => 'other_' . time() . '@example.com',
        ], '', true, true);
        $profile->save();

        $result = $this->modx->runProcessor(Update::class, [
            'username' => $takenUsername,
            'fullname' => $this->testProfile->get('fullname'),
            'email' => $this->testProfile->get('email'),
            'newpassword' => 'false',
        ]);

        $profile->remove();
        $otherUser->remove();

        $this->assertNotNull($result);
        $this->assertTrue($result->isError(), 'Expected failure when username is taken');
        $errors = $result->getFieldErrors();
        $usernameErrors = array_filter($errors, function ($e) {
            return $e->field === 'username';
        });
        $this->assertNotEmpty($usernameErrors, 'Expected username field error');
    }

    /**
     * Accepts valid username change and returns updated username in response.
     */
    public function testUsernameChangeSuccess()
    {
        $newUsername = 'profile_updated_' . time();

        $result = $this->modx->runProcessor(Update::class, [
            'username' => $newUsername,
            'fullname' => $this->testProfile->get('fullname'),
            'email' => $this->testProfile->get('email'),
            'newpassword' => 'false',
        ]);

        $this->assertNotNull($result);
        $this->assertFalse($result->isError(), $result->getMessage());

        $object = $result->getObject();
        $this->assertArrayHasKey('username', $object);
        $this->assertSame($newUsername, $object['username']);

        $reloaded = $this->modx->getObject(modUser::class, $this->testUser->get('id'));
        $this->assertNotNull($reloaded);
        $this->assertSame($newUsername, $reloaded->get('username'));
    }
}
