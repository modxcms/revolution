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
use MODX\Revolution\Security\PasswordResetToken;

/**
 * Tests for the signed password-reset / magic-login token helper.
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Security
 * @group PasswordResetToken
 */
class PasswordResetTokenTest extends MODxTestCase
{
    public const USERNAME = 'prt-unit-test-user';
    public const HASH = 'a1b2c3d4e5f6a1b2c3d4e5f6a1b2c3d4';

    /** @var modUser $user */
    protected $user;

    /**
     * Persist a fixture user so verify()'s username lookup resolves.
     *
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->removeFixtures();

        $user = $this->modx->newObject(modUser::class);
        $user->fromArray([
            'username' => self::USERNAME,
            'active' => true,
        ]);
        // Exercise the real hashing path so get('password') is realistic key material.
        $user->set('password', 'unit-test-password');
        if (!$user->save()) {
            $this->fail('Could not save fixture user for PasswordResetTokenTest');
        }
        $this->user = $user;
    }

    /**
     * @after
     */
    public function tearDownFixtures()
    {
        $this->removeFixtures();
    }

    protected function removeFixtures()
    {
        $existing = $this->modx->getObject(modUser::class, ['username' => self::USERNAME]);
        if ($existing) {
            $existing->remove();
        }
    }

    /**
     * A signed value verifies back to the same user for the matching topic + hash.
     */
    public function testSignVerifyRoundTrip()
    {
        $value = PasswordResetToken::sign($this->user, '/pwd/change/', self::HASH);
        $this->assertIsArray($value);
        $this->assertSame(self::USERNAME, $value['u']);
        $this->assertArrayHasKey('m', $value);

        $user = PasswordResetToken::verify($this->modx, '/pwd/change/', self::HASH, $value);
        $this->assertInstanceOf(modUser::class, $user);
        $this->assertSame(self::USERNAME, $user->get('username'));
    }

    /**
     * Legacy / forged bare-string values (the old token format) are rejected.
     */
    public function testRejectsLegacyStringValue()
    {
        $this->assertNull(
            PasswordResetToken::verify($this->modx, '/pwd/change/', self::HASH, self::USERNAME)
        );
    }

    /**
     * A value with a wrong signature is rejected.
     */
    public function testRejectsTamperedMac()
    {
        $value = PasswordResetToken::sign($this->user, '/pwd/change/', self::HASH);
        $value['m'] = 'deadbeef';
        $this->assertNull(
            PasswordResetToken::verify($this->modx, '/pwd/change/', self::HASH, $value)
        );
    }

    /**
     * A value missing the signature entirely is rejected.
     */
    public function testRejectsMissingMac()
    {
        $this->assertNull(
            PasswordResetToken::verify($this->modx, '/pwd/change/', self::HASH, ['u' => self::USERNAME])
        );
    }

    /**
     * A token issued for one topic cannot be replayed against another.
     */
    public function testRejectsCrossTopicReplay()
    {
        $value = PasswordResetToken::sign($this->user, '/pwd/change/', self::HASH);
        $this->assertNull(
            PasswordResetToken::verify($this->modx, '/pwd/magiclink/', self::HASH, $value)
        );
    }

    /**
     * Swapping the username in a value (forgery attempt) invalidates the signature.
     */
    public function testRejectsSwappedUsername()
    {
        $value = PasswordResetToken::sign($this->user, '/pwd/change/', self::HASH);
        $value['u'] = 'some-other-user';
        $this->assertNull(
            PasswordResetToken::verify($this->modx, '/pwd/change/', self::HASH, $value)
        );
    }

    /**
     * A value only verifies for the exact hash it was signed with.
     */
    public function testRejectsHashMismatch()
    {
        $value = PasswordResetToken::sign($this->user, '/pwd/change/', self::HASH);
        $this->assertNull(
            PasswordResetToken::verify($this->modx, '/pwd/change/', 'a-different-hash', $value)
        );
    }

    /**
     * An unknown username is rejected (no user to key the signature on).
     */
    public function testRejectsUnknownUser()
    {
        $value = [
            'u' => 'no-such-user-exists',
            'm' => 'anything',
        ];
        $this->assertNull(
            PasswordResetToken::verify($this->modx, '/pwd/change/', self::HASH, $value)
        );
    }
}
