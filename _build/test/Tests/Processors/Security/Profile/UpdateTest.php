<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For complete copyright and license information, please view the LICENSE
 * file that was distributed with this distribution.
 *
 * @package modx-test
 */
namespace MODX\Revolution\Tests\Processors\Security\Profile;

use MODX\Revolution\modUser;
use MODX\Revolution\modUserProfile;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\ProcessorResponse;
use MODX\Revolution\Processors\Security\Profile\Update;

/**
 * Tests for Security/Profile/Update processor (backup_email validation)
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Security
 * @group Profile
 */
class UpdateTest extends MODxTestCase
{
    /** @var modUser */
    private $testUser;

    /** @var modUser|null */
    private $otherUser;

    /**
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();

        $suffix = uniqid('', true);

        $this->testUser = $this->modx->newObject(modUser::class);
        $this->testUser->fromArray([
            'username' => 'profile_update_test_' . $suffix,
            'password' => md5('test'),
            'active' => true,
        ]);
        $this->testUser->save();

        $profile = $this->modx->newObject(modUserProfile::class);
        $profile->fromArray([
            'internalKey' => $this->testUser->get('id'),
            'email' => 'primary_' . $suffix . '@example.com',
            'fullname' => 'Test User',
            'backup_email' => '',
        ]);
        $profile->save();

        $this->otherUser = $this->modx->newObject(modUser::class);
        $this->otherUser->fromArray([
            'username' => 'profile_update_other_' . $suffix,
            'password' => md5('test'),
            'active' => true,
        ]);
        $this->otherUser->save();

        $otherProfile = $this->modx->newObject(modUserProfile::class);
        $otherProfile->fromArray([
            'internalKey' => $this->otherUser->get('id'),
            'email' => 'other_' . $suffix . '@example.com',
            'fullname' => 'Other User',
            'backup_email' => 'other_backup_' . $suffix . '@example.com',
        ]);
        $otherProfile->save();

        $this->modx->user = $this->testUser;
    }

    /**
     * @after
     */
    public function tearDownFixtures()
    {
        foreach ([$this->testUser, $this->otherUser] as $user) {
            if (!$user) {
                continue;
            }
            $profile = $user->getOne('Profile');
            if ($profile) {
                $profile->remove();
            }
            $user->remove();
        }
        parent::tearDownFixtures();
    }

    /**
     * @param bool $shouldPass
     * @param string $backupEmail
     * @param string|null $primaryEmail
     * @param string|null $expectedErrorFragment
     * @dataProvider providerBackupEmailValidation
     */
    public function testBackupEmailValidation(
        bool $shouldPass,
        string $backupEmail,
        ?string $primaryEmail = null,
        ?string $expectedErrorFragment = null
    ) {
        $profile = $this->testUser->getOne('Profile');
        $primary = $primaryEmail ?? $profile->get('email');

        $result = $this->modx->runProcessor(Update::class, [
            'email' => $primary,
            'fullname' => 'Test User',
            'backup_email' => $backupEmail,
            'newpassword' => 'false',
        ]);

        $this->assertInstanceOf(ProcessorResponse::class, $result);

        if ($shouldPass) {
            $this->assertFalse($result->isError(), 'Expected success: ' . $result->getMessage());
        } else {
            $this->assertTrue($result->isError(), 'Expected validation error');
            if ($expectedErrorFragment) {
                $fieldErrors = $result->getFieldErrors();
                $backupEmailErrors = array_filter($fieldErrors, static fn($e) => $e->field === 'backup_email');
                $this->assertNotEmpty($backupEmailErrors, 'Expected backup_email field error');
                $msg = reset($backupEmailErrors)->message;
                $this->assertStringContainsString($expectedErrorFragment, $msg);
            }
        }
    }

    public function providerBackupEmailValidation(): array
    {
        return [
            'valid backup email' => [true, 'backup_unique_' . uniqid() . '@example.com', null, null],
            'empty backup email allowed' => [true, '', null, null],
            'backup same as primary fails' => [
                false,
                'same@example.com',
                'same@example.com',
                'Backup email cannot be the same',
            ],
            'invalid email format fails' => [false, 'not-an-email', null, 'valid email'],
        ];
    }

    public function testBackupEmailCannotReuseOtherUserEmail()
    {
        $otherProfile = $this->otherUser->getOne('Profile');
        $result = $this->modx->runProcessor(Update::class, [
            'email' => $this->testUser->getOne('Profile')->get('email'),
            'fullname' => 'Test User',
            'backup_email' => $otherProfile->get('email'),
            'newpassword' => 'false',
        ]);

        $this->assertTrue($result->isError());
        $fieldErrors = $result->getFieldErrors();
        $backupEmailErrors = array_filter($fieldErrors, static fn($e) => $e->field === 'backup_email');
        $this->assertNotEmpty($backupEmailErrors);
    }

    public function testBackupEmailCannotReuseOtherUserBackupEmail()
    {
        $otherProfile = $this->otherUser->getOne('Profile');
        $result = $this->modx->runProcessor(Update::class, [
            'email' => $this->testUser->getOne('Profile')->get('email'),
            'fullname' => 'Test User',
            'backup_email' => $otherProfile->get('backup_email'),
            'newpassword' => 'false',
        ]);

        $this->assertTrue($result->isError());
        $fieldErrors = $result->getFieldErrors();
        $backupEmailErrors = array_filter($fieldErrors, static fn($e) => $e->field === 'backup_email');
        $this->assertNotEmpty($backupEmailErrors);
    }
}
