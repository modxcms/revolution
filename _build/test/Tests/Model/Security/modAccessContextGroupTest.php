<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package modx-test
 */
namespace MODX\Revolution\Tests\Model\Security;

use MODX\Revolution\modAccessContext;
use MODX\Revolution\modAccessContextGroup;
use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modContext;
use MODX\Revolution\modContextGroup;
use MODX\Revolution\modUser;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\modUserGroupMember;
use MODX\Revolution\modUserGroupRole;
use MODX\Revolution\MODxTestCase;

/**
 * @group Model
 * @group Security
 * @group AccessContextGroup
 */
class modAccessContextGroupTest extends MODxTestCase
{
    /** @var modContextGroup|null */
    private $group;
    /** @var modUserGroup|null */
    private $userGroup;
    /** @var modUser|null */
    private $user;
    /** @var int */
    private $policyId = 0;
    /** @var int */
    private $roleId = 0;

    /**
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->modx->getManager()->createObjectContainer(modAccessContextGroup::class);

        $this->group = $this->modx->newObject(modContextGroup::class);
        $this->group->fromArray([
            'name' => 'UnitTestAclGroup',
            'description' => 'ACL fixture',
            'rank' => 1,
        ], '', true, true);
        $this->group->save();

        foreach (['utaclen', 'utaclnl'] as $i => $key) {
            $ctx = $this->modx->newObject(modContext::class);
            $ctx->fromArray([
                'key' => $key,
                'name' => 'Unit Test ACL ' . $key,
                'context_group' => $this->group->get('id'),
                'rank' => $i,
            ], '', true, true);
            $ctx->save();
        }

        $ungrouped = $this->modx->newObject(modContext::class);
        $ungrouped->fromArray([
            'key' => 'utaclout',
            'name' => 'Unit Test ACL Ungrouped',
            'context_group' => 0,
            'rank' => 0,
        ], '', true, true);
        $ungrouped->save();

        $this->userGroup = $this->modx->newObject(modUserGroup::class);
        $this->userGroup->fromArray([
            'name' => 'UnitTestAclEditors',
            'description' => 'ACL editors',
        ], '', true, true);
        $this->userGroup->save();

        $role = $this->modx->getObject(modUserGroupRole::class, ['name' => 'Member']);
        $this->roleId = $role ? (int)$role->get('id') : 1;

        $this->user = $this->modx->newObject(modUser::class);
        $this->user->fromArray([
            'username' => 'unittestacleditor',
            'password' => 'testpassword',
            'active' => 1,
        ], '', true, true);
        $this->user->save();

        $member = $this->modx->newObject(modUserGroupMember::class);
        $member->fromArray([
            'user_group' => $this->userGroup->get('id'),
            'member' => $this->user->get('id'),
            'role' => $this->roleId,
        ], '', true, true);
        $member->save();

        $policy = $this->modx->getObject(modAccessPolicy::class, ['name' => 'Context']);
        $this->policyId = $policy ? (int)$policy->get('id') : 0;

        $acl = $this->modx->newObject(modAccessContextGroup::class);
        $acl->fromArray([
            'target' => $this->group->get('id'),
            'principal_class' => modUserGroup::class,
            'principal' => $this->userGroup->get('id'),
            'authority' => 9999,
            'policy' => $this->policyId,
        ], '', true, true);
        $acl->save();
    }

    /**
     * @after
     */
    public function tearDownFixtures()
    {
        parent::tearDownFixtures();

        $this->modx->removeCollection(modAccessContextGroup::class, [
            'principal' => $this->userGroup ? $this->userGroup->get('id') : -1,
        ]);
        $this->modx->removeCollection(modAccessContext::class, [
            'target:IN' => ['utaclen', 'utaclnl', 'utaclout'],
        ]);
        $this->modx->removeCollection(modUserGroupMember::class, [
            'member' => $this->user ? $this->user->get('id') : -1,
        ]);
        if ($this->user) {
            $this->user->remove();
        }
        if ($this->userGroup) {
            $this->userGroup->remove();
        }

        foreach (['utaclen', 'utaclnl', 'utaclout'] as $key) {
            $ctx = $this->modx->getObject(modContext::class, $key);
            if ($ctx) {
                $ctx->remove();
            }
        }
        if ($this->group) {
            $this->group->remove();
        }
        $this->modx->error->reset();
    }

    public function testGroupAclExpandsToMemberContexts()
    {
        $attrs = modAccessContext::loadAttributes($this->modx, 'mgr', $this->user->get('id'));
        $this->assertArrayHasKey('utaclen', $attrs);
        $this->assertArrayHasKey('utaclnl', $attrs);
        $this->assertArrayNotHasKey('utaclout', $attrs);
    }

    public function testFindPolicyIncludesGroupAcl()
    {
        $ctx = $this->modx->getObject(modContext::class, 'utaclen');
        $this->assertNotNull($ctx);
        $ctx->_policies = [];
        // Clear cached policies via public API when the object may already be warm.
        if (method_exists($ctx, 'setPolicies')) {
            $ctx->setPolicies([]);
        }
        $policy = $ctx->findPolicy('utaclen');
        $this->assertArrayHasKey(modAccessContext::class, $policy);
        $this->assertArrayHasKey('utaclen', $policy[modAccessContext::class]);
        $found = false;
        foreach ($policy[modAccessContext::class]['utaclen'] as $entry) {
            if ((int)$entry['principal'] === (int)$this->userGroup->get('id')) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found);
    }

    public function testAuthorityGate()
    {
        $acl = $this->modx->getObject(modAccessContextGroup::class, [
            'principal' => $this->userGroup->get('id'),
            'target' => $this->group->get('id'),
        ]);
        $this->assertNotNull($acl);
        $acl->set('authority', 0);
        $acl->save();

        $attrs = modAccessContext::loadAttributes($this->modx, 'mgr', $this->user->get('id'));
        $this->assertArrayNotHasKey('utaclen', $attrs);
    }

    public function testAdditiveWithPerContextAcl()
    {
        $direct = $this->modx->newObject(modAccessContext::class);
        $direct->fromArray([
            'target' => 'utaclout',
            'principal_class' => modUserGroup::class,
            'principal' => $this->userGroup->get('id'),
            'authority' => 9999,
            'policy' => $this->policyId,
        ], '', true, true);
        $direct->save();

        $attrs = modAccessContext::loadAttributes($this->modx, 'mgr', $this->user->get('id'));
        $this->assertArrayHasKey('utaclen', $attrs);
        $this->assertArrayHasKey('utaclout', $attrs);
    }
}
