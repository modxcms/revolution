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

    /**
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();

        $user = $this->modx->getObject(modUser::class, 1);
        if (!$user || !$user->getOne('Profile')) {
            $this->testUser = $this->modx->newObject(modUser::class);
            $this->testUser->fromArray([
                'username' => 'profile_update_test_' . uniqid(),
                'password' => md5('test'),
                'active' => true,
            ]);
            $this->testUser->save();

            $profile = $this->modx->newObject(modUserProfile::class);
            $profile->set('email', 'primary@example.com');
            $profile->set('fullname', 'Test User');
            $profile->set('internalKey', $this->testUser->get('id'));
            $profile->save();
        } else {
            $this->testUser = $user;
        }

        $this->modx->user = $this->testUser;
    }

    /**
     * @after
     */
    public function tearDownFixtures()
    {
        if ($this->testUser && strpos($this->testUser->get('username'), 'profile_update_test_') === 0) {
            $profile = $this->testUser->getOne('Profile');
            if ($profile) {
                $profile->remove();
            }
            $this->testUser->remove();
        }
        parent::tearDownFixtures();
    }

    /**
     * @param bool $shouldPass
     * @param string $backupEmail
     * @param string $primaryEmail
     * @param string|null $expectedErrorKey
     * @dataProvider providerBackupEmailValidation
     */
    public function testBackupEmailValidation(
        bool $shouldPass,
        string $backupEmail,
        string $primaryEmail,
        ?string $expectedErrorKey = null
    ) {
        $result = $this->modx->runProcessor(Update::class, [
            'email' => $primaryEmail,
            'fullname' => 'Test User',
            'backup_email' => $backupEmail,
            'newpassword' => 'false',
        ]);

        $this->assertInstanceOf(ProcessorResponse::class, $result);

        if ($shouldPass) {
            $this->assertFalse($result->isError(), 'Expected success: ' . $result->getMessage());
        } else {
            $this->assertTrue($result->isError(), 'Expected validation error');
            if ($expectedErrorKey) {
                $fieldErrors = $result->getFieldErrors();
                $backupEmailErrors = array_filter($fieldErrors, fn($e) => $e->field === 'backup_email');
                $this->assertNotEmpty($backupEmailErrors, 'Expected backup_email field error');
                $msg = reset($backupEmailErrors)->message;
                $this->assertStringContainsString($expectedErrorKey, $msg, 'Expected error key in message');
            }
        }
    }

    /**
     * @return array
     */
    public function providerBackupEmailValidation(): array
    {
        return [
            'valid backup email' => [true, 'backup@example.com', 'primary@example.com', null],
            'empty backup email allowed' => [true, '', 'primary@example.com', null],
            'backup same as primary fails' => [
                false,
                'same@example.com',
                'same@example.com',
                'Backup email cannot be the same',
            ],
            'invalid email format fails' => [false, 'not-an-email', 'primary@example.com', 'valid email'],
        ];
    }
}
