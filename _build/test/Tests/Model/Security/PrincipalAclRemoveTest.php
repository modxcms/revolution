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

use MODX\Revolution\modAccessContext;
use MODX\Revolution\modAccessMenu;
use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\MODxTestCase;

/**
 * Regression for #11276: deleting principals must not query abstract modAccess
 * and must clear concrete ACL rows (including classes outside principal_targets).
 *
 * @package modx-test
 * @subpackage modx
 * @group Model
 * @group Security
 * @group modPrincipal
 */
class PrincipalAclRemoveTest extends MODxTestCase
{
    /** @var string */
    private $username = 'unittest-acl-user';

    /** @var string */
    private $groupName = 'UnitTestAclGroup';

    /**
     * @after
     */
    public function tearDownFixtures()
    {
        $user = $this->modx->getObject(modUser::class, ['username' => $this->username]);
        if ($user) {
            $user->remove();
        }

        $group = $this->modx->getObject(modUserGroup::class, ['name' => $this->groupName]);
        if ($group) {
            $group->remove();
        }

        parent::tearDownFixtures();
    }

    public function testUserRemoveClearsAclsWithoutModAccessTableErrors()
    {
        $policy = $this->modx->getObject(modAccessPolicy::class, ['name' => 'Administrator']);
        if (!$policy) {
            $this->markTestSkipped('Administrator access policy is not available.');
        }

        /** @var modUser $user */
        $user = $this->modx->newObject(modUser::class);
        $user->fromArray([
            'username' => $this->username,
            'password' => 'unittest-password',
            'active' => true,
            'class_key' => modUser::class,
        ], '', true, true);
        $this->assertTrue((bool)$user->save(), 'Could not create user fixture.');

        $principalId = (int)$user->get('id');
        $this->assertTrue(
            $this->createContextAcl(modUser::class, $principalId, (int)$policy->get('id')),
            'Could not create context ACL fixture.'
        );
        $this->assertTrue(
            $this->createMenuAcl(modUser::class, $principalId, (int)$policy->get('id')),
            'Could not create menu ACL fixture.'
        );

        $this->assertTrue((bool)$user->remove(), 'User remove() failed.');
        $this->assertNull(
            $this->modx->getObject(modUser::class, ['username' => $this->username]),
            'User row should be gone after remove().'
        );
        $this->assertSame(
            0,
            $this->modx->getCount(modAccessContext::class, [
                'principal_class' => modUser::class,
                'principal' => $principalId,
            ]),
            'Context ACL rows must be removed with the user.'
        );
        $this->assertSame(
            0,
            $this->modx->getCount(modAccessMenu::class, [
                'principal_class' => modUser::class,
                'principal' => $principalId,
            ]),
            'Menu ACL rows must be removed with the user.'
        );
    }

    public function testUserGroupRemoveClearsAcls()
    {
        $policy = $this->modx->getObject(modAccessPolicy::class, ['name' => 'Context']);
        if (!$policy) {
            $this->markTestSkipped('Context access policy is not available.');
        }

        /** @var modUserGroup $group */
        $group = $this->modx->newObject(modUserGroup::class);
        $group->fromArray([
            'name' => $this->groupName,
        ], '', true, true);
        $this->assertTrue((bool)$group->save(), 'Could not create user group fixture.');

        $principalId = (int)$group->get('id');
        $this->assertTrue(
            $this->createContextAcl(modUserGroup::class, $principalId, (int)$policy->get('id')),
            'Could not create group ACL fixture.'
        );

        $this->assertTrue((bool)$group->remove(), 'User group remove() failed.');
        $this->assertSame(
            0,
            $this->modx->getCount(modAccessContext::class, [
                'principal_class' => modUserGroup::class,
                'principal' => $principalId,
            ]),
            'Expected no leftover context ACL rows after group remove.'
        );
    }

    public function testUserRemoveClearsAclsOutsidePrincipalTargets()
    {
        $policy = $this->modx->getObject(modAccessPolicy::class, ['name' => 'Administrator']);
        if (!$policy) {
            $this->markTestSkipped('Administrator access policy is not available.');
        }

        $originalTargets = $this->modx->getOption('principal_targets');
        $this->modx->setOption('principal_targets', modAccessContext::class);

        /** @var modUser $user */
        $user = $this->modx->newObject(modUser::class);
        $user->fromArray([
            'username' => $this->username,
            'password' => 'unittest-password',
            'active' => true,
            'class_key' => modUser::class,
        ], '', true, true);
        $this->assertTrue((bool)$user->save(), 'Could not create user fixture.');
        $principalId = (int)$user->get('id');

        $this->assertTrue(
            $this->createMenuAcl(modUser::class, $principalId, (int)$policy->get('id')),
            'Could not create menu ACL fixture outside principal_targets.'
        );

        $this->assertTrue((bool)$user->remove(), 'User remove() failed.');
        $this->assertSame(
            0,
            $this->modx->getCount(modAccessMenu::class, [
                'principal_class' => modUser::class,
                'principal' => $principalId,
            ]),
            'ACL rows outside principal_targets must still be removed.'
        );

        $this->modx->setOption('principal_targets', $originalTargets);
    }

    /**
     * @param string $principalClass
     * @param int $principalId
     * @param int $policyId
     * @return bool
     */
    private function createContextAcl(string $principalClass, int $principalId, int $policyId): bool
    {
        $acl = $this->modx->newObject(modAccessContext::class);
        $acl->fromArray([
            'target' => 'web',
            'principal_class' => $principalClass,
            'principal' => $principalId,
            'authority' => 9999,
            'policy' => $policyId,
        ], '', true, true);

        return (bool)$acl->save();
    }

    /**
     * @param string $principalClass
     * @param int $principalId
     * @param int $policyId
     * @return bool
     */
    private function createMenuAcl(string $principalClass, int $principalId, int $policyId): bool
    {
        $acl = $this->modx->newObject(modAccessMenu::class);
        $acl->fromArray([
            'target' => '0',
            'principal_class' => $principalClass,
            'principal' => $principalId,
            'authority' => 9999,
            'policy' => $policyId,
        ], '', true, true);

        return (bool)$acl->save();
    }
}
