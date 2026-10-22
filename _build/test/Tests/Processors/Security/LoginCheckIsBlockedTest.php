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

namespace MODX\Revolution\Tests\Processors\Security;

use MODX\Revolution\modUser;
use MODX\Revolution\modUserProfile;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Security\Login;

/**
 * Tests for Login::checkIsBlocked() block-expiry behavior.
 *
 * @package modx-test
 * @subpackage Processors
 * @group Processors
 * @group Security
 * @group Login
 */
class LoginCheckIsBlockedTest extends MODxTestCase
{
    /** @var modUser */
    protected $testUser;

    /** @var modUserProfile */
    protected $testProfile;

    /** @var int */
    protected $originalBlocked;

    /** @var int */
    protected $originalBlockedUntil;

    /** @var int */
    protected $originalFailedLoginCount;

    /**
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->modx->lexicon->load('login');

        $this->testUser = $this->modx->getObject(modUser::class, 1);
        if (!$this->testUser || !$this->testUser->getOne('Profile')) {
            $this->markTestSkipped('Test requires admin user with profile in database');
        }

        $this->testProfile = $this->testUser->getOne('Profile');
        $this->originalBlocked = (int)$this->testProfile->get('blocked');
        $this->originalBlockedUntil = (int)$this->testProfile->get('blockeduntil');
        $this->originalFailedLoginCount = (int)$this->testProfile->get('failedlogincount');
    }

    /**
     * @after
     */
    public function tearDownFixtures()
    {
        if ($this->testProfile) {
            $this->testProfile->set('blocked', $this->originalBlocked);
            $this->testProfile->set('blockeduntil', $this->originalBlockedUntil);
            $this->testProfile->set('failedlogincount', $this->originalFailedLoginCount);
            $this->testProfile->save();
        }
        parent::tearDownFixtures();
    }

    /**
     * @param array<string, int> $profileOverrides
     * @return bool|string|null
     */
    protected function runCheckIsBlocked(array $profileOverrides)
    {
        foreach ($profileOverrides as $key => $value) {
            $this->testProfile->set($key, $value);
        }
        $this->testProfile->save();
        $this->testUser->addOne($this->testProfile, 'Profile');

        $login = new Login($this->modx, []);
        $login->user = $this->testUser;

        return $login->checkIsBlocked();
    }

    public function testActiveTemporaryBlockReturnsBlockedError()
    {
        $result = $this->runCheckIsBlocked([
            'blocked' => 1,
            'blockeduntil' => time() + 3600,
            'failedlogincount' => 0,
        ]);

        $this->assertSame($this->modx->lexicon('login_blocked_error'), $result);
    }

    public function testExpiredTemporaryBlockUnblocksAndReturnsFalse()
    {
        $result = $this->runCheckIsBlocked([
            'blocked' => 1,
            'blockeduntil' => time() - 60,
            'failedlogincount' => 5,
        ]);

        $this->assertFalse($result);

        $reloaded = $this->modx->getObject(modUserProfile::class, ['internalKey' => $this->testUser->get('id')]);
        $this->assertNotNull($reloaded);
        $this->assertSame(0, (int)$reloaded->get('blocked'));
        $this->assertSame(0, (int)$reloaded->get('failedlogincount'));
    }

    public function testPermanentAdminBlockReturnsAdminMessage()
    {
        $result = $this->runCheckIsBlocked([
            'blocked' => 1,
            'blockeduntil' => 0,
            'failedlogincount' => 0,
        ]);

        $this->assertSame($this->modx->lexicon('login_blocked_admin'), $result);
    }
}
