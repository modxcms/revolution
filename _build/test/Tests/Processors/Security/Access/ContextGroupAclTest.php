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
namespace MODX\Revolution\Tests\Processors\Security\Access;

use MODX\Revolution\modAccessContextGroup;
use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modContext;
use MODX\Revolution\modContextGroup;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Context\Group\Remove as ContextGroupRemove;
use MODX\Revolution\Processors\Security\Access\UserGroup\ContextGroup\Create;
use MODX\Revolution\Processors\Security\Access\UserGroup\ContextGroup\GetList;
use MODX\Revolution\Processors\Security\Access\UserGroup\ContextGroup\Remove;
use MODX\Revolution\Processors\Security\Access\UserGroup\ContextGroup\Update;

/**
 * @group Processors
 * @group Security
 * @group AccessContextGroup
 */
class ContextGroupAclTest extends MODxTestCase
{
    /** @var modContextGroup|null */
    private $group;
    /** @var modUserGroup|null */
    private $editors;
    /** @var int */
    private $policyId = 0;

    /**
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->modx->getManager()->createObjectContainer(modAccessContextGroup::class);

        $this->group = $this->modx->newObject(modContextGroup::class);
        $this->group->fromArray([
            'name' => 'UnitTestProcAclGroup',
            'rank' => 1,
        ], '', true, true);
        $this->group->save();

        $ctx = $this->modx->newObject(modContext::class);
        $ctx->fromArray([
            'key' => 'utprocacl',
            'name' => 'Unit Test Proc ACL',
            'context_group' => $this->group->get('id'),
        ], '', true, true);
        $ctx->save();

        $this->editors = $this->modx->newObject(modUserGroup::class);
        $this->editors->fromArray([
            'name' => 'UnitTestProcAclEditors',
        ], '', true, true);
        $this->editors->save();

        $policy = $this->modx->getObject(modAccessPolicy::class, ['name' => 'Context']);
        $this->policyId = $policy ? (int)$policy->get('id') : 0;
    }

    /**
     * @after
     */
    public function tearDownFixtures()
    {
        parent::tearDownFixtures();
        $this->modx->removeCollection(modAccessContextGroup::class, [
            'target' => $this->group ? $this->group->get('id') : -1,
        ]);
        $ctx = $this->modx->getObject(modContext::class, 'utprocacl');
        if ($ctx) {
            $ctx->remove();
        }
        if ($this->editors) {
            $this->editors->remove();
        }
        if ($this->group) {
            $this->group->remove();
        }
        $groups = $this->modx->getCollection(modUserGroup::class, [
            'name:LIKE' => 'UnitTestProcAcl%',
        ]);
        foreach ($groups as $group) {
            $group->remove();
        }
        $this->modx->error->reset();
    }

    public function testCreateAndListAcl()
    {
        $response = $this->modx->runProcessor(Create::class, [
            'principal' => $this->editors->get('id'),
            'target' => $this->group->get('id'),
            'policy' => $this->policyId,
            'authority' => 9999,
        ]);
        $this->assertTrue($this->checkForSuccess($response), $response->getMessage());

        $list = $this->modx->runProcessor(GetList::class, [
            'usergroup' => $this->editors->get('id'),
            'limit' => 10,
        ]);
        $this->assertTrue($this->checkForSuccess($list), $list->getMessage());
        $results = $this->getResults($list);
        $this->assertNotEmpty($results);

        $admin = $this->modx->getObject(modUserGroup::class, ['name' => 'Administrator']);
        $this->assertNotNull($admin);
        $adminAcl = $this->modx->getObject(modAccessContextGroup::class, [
            'principal' => $admin->get('id'),
            'target' => $this->group->get('id'),
        ]);
        $this->assertNotNull($adminAcl, 'Admin safeguard should create Administrator group ACL');
        $this->assertSame(9999, (int)$adminAcl->get('authority'));
    }

    public function testDuplicateAclFails()
    {
        $this->modx->runProcessor(Create::class, [
            'principal' => $this->editors->get('id'),
            'target' => $this->group->get('id'),
            'policy' => $this->policyId,
            'authority' => 9999,
        ]);
        $this->modx->error->reset();

        $response = $this->modx->runProcessor(Create::class, [
            'principal' => $this->editors->get('id'),
            'target' => $this->group->get('id'),
            'policy' => $this->policyId,
            'authority' => 9999,
        ]);
        $this->assertFalse($this->checkForSuccess($response));
    }

    public function testUpdateAndRemoveAcl()
    {
        $create = $this->modx->runProcessor(Create::class, [
            'principal' => $this->editors->get('id'),
            'target' => $this->group->get('id'),
            'policy' => $this->policyId,
            'authority' => 9999,
        ]);
        $this->assertTrue($this->checkForSuccess($create), $create->getMessage());
        $object = $create->getObject();
        $this->assertNotEmpty($object['id']);

        $update = $this->modx->runProcessor(Update::class, [
            'id' => $object['id'],
            'principal' => $this->editors->get('id'),
            'target' => $this->group->get('id'),
            'policy' => $this->policyId,
            'authority' => 0,
        ]);
        $this->assertTrue($this->checkForSuccess($update), $update->getMessage());

        $remove = $this->modx->runProcessor(Remove::class, [
            'id' => $object['id'],
        ]);
        $this->assertTrue($this->checkForSuccess($remove), $remove->getMessage());
        $this->assertNull($this->modx->getObject(modAccessContextGroup::class, $object['id']));
    }

    public function testContextGroupRemoveCascadesAcl()
    {
        $this->modx->runProcessor(Create::class, [
            'principal' => $this->editors->get('id'),
            'target' => $this->group->get('id'),
            'policy' => $this->policyId,
            'authority' => 9999,
        ]);
        $groupId = $this->group->get('id');
        $this->assertGreaterThan(0, $this->modx->getCount(modAccessContextGroup::class, [
            'target' => $groupId,
        ]));

        $response = $this->modx->runProcessor(ContextGroupRemove::class, [
            'id' => $groupId,
        ]);
        $this->assertTrue($this->checkForSuccess($response), $response->getMessage());
        $this->assertSame(0, $this->modx->getCount(modAccessContextGroup::class, [
            'target' => $groupId,
        ]));
        $this->group = null;
    }
}
