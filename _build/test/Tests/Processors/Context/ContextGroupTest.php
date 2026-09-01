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
namespace MODX\Revolution\Tests\Processors\Context;

use ReflectionMethod;
use MODX\Revolution\modAccessContextGroup;
use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modContext;
use MODX\Revolution\modContextGroup;
use MODX\Revolution\modResource;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Context\Create as ContextCreate;
use MODX\Revolution\Processors\Context\Group\Create;
use MODX\Revolution\Processors\Context\Group\GetList;
use MODX\Revolution\Processors\Context\Group\Remove;
use MODX\Revolution\Processors\Context\Group\Update;
use MODX\Revolution\Processors\Context\Group\UpdateFromGrid;
use MODX\Revolution\Processors\Context\Update as ContextUpdate;
use MODX\Revolution\Processors\Context\UpdateFromGrid as ContextUpdateFromGrid;
use MODX\Revolution\Processors\ProcessorResponse;
use MODX\Revolution\Processors\Resource\GetNodes;
use MODX\Revolution\Processors\Resource\Sort as ResourceSort;

/**
 * Tests related to Context Group processors and Resource tree grouping.
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Context
 * @group ContextGroup
 * @group ContextGroupProcessors
 */
class ContextGroupTest extends MODxTestCase
{
    /**
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->modx->getManager()->createObjectContainer(modAccessContextGroup::class);

        $group = $this->modx->newObject(modContextGroup::class);
        $group->fromArray([
            'name' => 'UnitTestGroup',
            'description' => 'Fixture group',
            'rank' => 1,
        ], '', true, true);
        $group->save();

        $ctx = $this->modx->newObject(modContext::class);
        $ctx->fromArray([
            'key' => 'unittestcg',
            'name' => 'Unit Test CG Context',
            'context_group' => $group->get('id'),
            'rank' => 0,
        ], '', true, true);
        $ctx->save();
    }

    /**
     * @after
     */
    public function tearDownFixtures()
    {
        parent::tearDownFixtures();

        $resources = $this->modx->getCollection(modResource::class, [
            'pagetitle:LIKE' => 'UnitTestCG%',
        ]);
        foreach ($resources as $resource) {
            $resource->remove();
        }

        $contexts = $this->modx->getCollection(modContext::class, [
            'key:LIKE' => '%unittestcg%',
        ]);
        foreach ($contexts as $ctx) {
            $ctx->remove();
        }

        $groups = $this->modx->getCollection(modContextGroup::class, [
            'name:LIKE' => 'UnitTest%',
        ]);
        $groupIds = [];
        foreach ($groups as $group) {
            $groupIds[] = (int)$group->get('id');
        }
        if ($groupIds) {
            $this->modx->removeCollection(modAccessContextGroup::class, [
                'target:IN' => $groupIds,
            ]);
        }
        foreach ($groups as $group) {
            $group->remove();
        }
        $this->modx->error->reset();
    }

    public function testContextGroupCreate()
    {
        /** @var ProcessorResponse $result */
        $result = $this->modx->runProcessor(Create::class, [
            'name' => 'UnitTestGroupCreated',
            'description' => 'Created by test',
            'rank' => 5,
        ]);
        $this->assertNotEmpty($result, 'Could not load Create processor');
        $this->assertTrue($this->checkForSuccess($result), $result->getMessage());
        $group = $this->modx->getObject(modContextGroup::class, ['name' => 'UnitTestGroupCreated']);
        $this->assertInstanceOf(modContextGroup::class, $group);
    }

    public function testContextGroupCreateDuplicateFails()
    {
        $result = $this->modx->runProcessor(Create::class, [
            'name' => 'UnitTestGroup',
        ]);
        $this->assertFalse($this->checkForSuccess($result));
    }

    public function testContextCreateRejectsMissingGroup()
    {
        $result = $this->modx->runProcessor(ContextCreate::class, [
            'key' => 'unittestcg_missing_group',
            'context_group' => 999999,
            'enableAnonymous' => false,
        ]);

        $this->assertFalse($this->checkForSuccess($result));
        $this->assertNull($this->modx->getObject(modContext::class, 'unittestcg_missing_group'));
    }

    public function testContextUpdateRejectsMissingGroup()
    {
        $context = $this->modx->getObject(modContext::class, 'unittestcg');
        $originalGroup = (int)$context->get('context_group');

        $result = $this->modx->runProcessor(ContextUpdate::class, [
            'key' => 'unittestcg',
            'context_group' => 999999,
        ]);

        $this->assertFalse($this->checkForSuccess($result));
        $context = $this->modx->getObject(modContext::class, 'unittestcg');
        $this->assertSame($originalGroup, (int)$context->get('context_group'));
    }

    public function testContextUpdateFromGridRejectsMissingGroup()
    {
        $context = $this->modx->getObject(modContext::class, 'unittestcg');
        $originalGroup = (int)$context->get('context_group');

        $result = $this->modx->runProcessor(ContextUpdateFromGrid::class, [
            'data' => json_encode([
                'key' => 'unittestcg',
                'context_group' => 999999,
            ]),
        ]);

        $this->assertFalse($this->checkForSuccess($result));
        $context = $this->modx->getObject(modContext::class, 'unittestcg');
        $this->assertSame($originalGroup, (int)$context->get('context_group'));
    }

    public function testContextGroupUpdate()
    {
        $group = $this->modx->getObject(modContextGroup::class, ['name' => 'UnitTestGroup']);
        $this->assertInstanceOf(modContextGroup::class, $group);

        $result = $this->modx->runProcessor(Update::class, [
            'id' => $group->get('id'),
            'name' => 'UnitTestGroupRenamed',
            'rank' => 9,
        ]);
        $this->assertTrue($this->checkForSuccess($result), $result->getMessage());
        $group = $this->modx->getObject(modContextGroup::class, ['id' => $group->get('id')]);
        $this->assertSame('UnitTestGroupRenamed', $group->get('name'));
        $this->assertSame(9, (int)$group->get('rank'));
    }

    public function testContextGroupGetList()
    {
        $result = $this->modx->runProcessor(GetList::class, [
            'limit' => 0,
        ]);
        $this->assertTrue($this->checkForSuccess($result), $result->getMessage());
        $object = $result->getObject();
        $this->assertTrue(is_array($object));
    }

    public function testContextGroupRemoveUnassignsContexts()
    {
        $group = $this->modx->getObject(modContextGroup::class, ['name' => 'UnitTestGroup']);
        $this->assertInstanceOf(modContextGroup::class, $group);
        $groupId = (int)$group->get('id');

        $result = $this->modx->runProcessor(Remove::class, [
            'id' => $groupId,
        ]);
        $this->assertTrue($this->checkForSuccess($result), $result->getMessage());
        $this->assertNull($this->modx->getObject(modContextGroup::class, $groupId));

        $ctx = $this->modx->getObject(modContext::class, ['key' => 'unittestcg']);
        $this->assertInstanceOf(modContext::class, $ctx);
        $this->assertSame(0, (int)$ctx->get('context_group'));
    }

    /**
     * Direct model remove must not cascade-delete member Contexts (aggregate),
     * must unassign them, and must still cascade ACL rows (composite).
     */
    public function testModelRemoveKeepsContextsAndClearsAcls()
    {
        $group = $this->modx->getObject(modContextGroup::class, ['name' => 'UnitTestGroup']);
        $this->assertInstanceOf(modContextGroup::class, $group);
        $groupId = (int)$group->get('id');

        $policy = $this->modx->getObject(modAccessPolicy::class, ['name' => 'Context']);
        $this->assertInstanceOf(modAccessPolicy::class, $policy);

        $acl = $this->modx->newObject(modAccessContextGroup::class);
        $acl->fromArray([
            'target' => $groupId,
            'principal_class' => modUserGroup::class,
            'principal' => 0,
            'authority' => 9999,
            'policy' => (int)$policy->get('id'),
        ], '', true, true);
        $this->assertTrue($acl->save());
        $aclId = (int)$acl->get('id');

        $this->assertTrue($group->remove());
        $this->assertNull($this->modx->getObject(modContextGroup::class, $groupId));

        $ctx = $this->modx->getObject(modContext::class, ['key' => 'unittestcg']);
        $this->assertInstanceOf(modContext::class, $ctx, 'Member Context must survive group remove');
        $this->assertSame(0, (int)$ctx->get('context_group'));

        $this->assertNull($this->modx->getObject(modAccessContextGroup::class, $aclId));
        $this->assertSame(0, $this->modx->getCount(modAccessContextGroup::class, ['target' => $groupId]));
    }

    public function testGetNodesFiltersByContextGroup()
    {
        $group = $this->modx->getObject(modContextGroup::class, ['name' => 'UnitTestGroup']);
        $this->assertInstanceOf(modContextGroup::class, $group);

        $this->modx->setOption('context_tree_group', false);
        /** @var ProcessorResponse|string $result */
        $result = $this->modx->runProcessor(GetNodes::class, [
            'id' => 'root',
            'context_group' => $group->get('id'),
            'stringLiterals' => true,
        ]);
        $payload = is_object($result) ? $result->getResponse() : $result;
        if (is_array($payload)) {
            $nodes = $payload;
        } else {
            $nodes = json_decode((string)$payload, true);
        }
        $this->assertTrue(is_array($nodes));
        $keys = [];
        foreach ($nodes as $node) {
            if (!empty($node['ctx'])) {
                $keys[] = $node['ctx'];
            }
        }
        $this->assertContains('unittestcg', $keys);
        $this->assertNotContains('mgr', $keys);
    }

    public function testGetNodesNestsContextGroups()
    {
        $group = $this->modx->getObject(modContextGroup::class, ['name' => 'UnitTestGroup']);
        $this->assertInstanceOf(modContextGroup::class, $group);
        $groupId = (int)$group->get('id');

        $this->modx->setOption('context_tree_group', true);
        /** @var ProcessorResponse|string $result */
        $result = $this->modx->runProcessor(GetNodes::class, [
            'id' => 'root',
            'context_group' => 'all',
            'stringLiterals' => true,
        ]);
        $payload = is_object($result) ? $result->getResponse() : $result;
        $nodes = is_array($payload) ? $payload : json_decode((string)$payload, true);
        $this->assertTrue(is_array($nodes));

        $groupNode = null;
        foreach ($nodes as $node) {
            if (($node['id'] ?? '') === 'cg-' . $groupId) {
                $groupNode = $node;
                break;
            }
        }
        $this->assertNotNull($groupNode, 'Expected nested Context Group node at root');
        $this->assertSame(modContextGroup::class, $groupNode['type']);
        $this->assertFalse((bool)$groupNode['allowDrop']);
        $this->assertFalse((bool)$groupNode['draggable']);

        $result = $this->modx->runProcessor(GetNodes::class, [
            'id' => 'cg-' . $groupId,
            'stringLiterals' => true,
        ]);
        $payload = is_object($result) ? $result->getResponse() : $result;
        $childNodes = is_array($payload) ? $payload : json_decode((string)$payload, true);
        $this->assertTrue(is_array($childNodes));
        $keys = [];
        foreach ($childNodes as $node) {
            if (!empty($node['ctx'])) {
                $keys[] = $node['ctx'];
            }
        }
        $this->assertContains('unittestcg', $keys);
    }

    public function testGetNodesSearchRespectsContextGroupFilter()
    {
        $group = $this->modx->getObject(modContextGroup::class, ['name' => 'UnitTestGroup']);
        foreach (['unittestcg', 'web'] as $contextKey) {
            $resource = $this->modx->newObject(modResource::class);
            $resource->fromArray([
                'pagetitle' => 'UnitTestCG Search ' . $contextKey,
                'context_key' => $contextKey,
                'parent' => 0,
                'published' => true,
                'show_in_tree' => true,
            ], '', true, true);
            $this->assertTrue($resource->save());
        }

        $result = $this->modx->runProcessor(GetNodes::class, [
            'id' => 'root',
            'context_group' => $group->get('id'),
            'search' => 'UnitTestCG Search',
            'stringLiterals' => true,
        ]);
        $payload = is_object($result) ? $result->getResponse() : $result;
        $nodes = is_array($payload) ? $payload : json_decode((string)$payload, true);
        $contexts = [];
        foreach ($nodes as $node) {
            if (($node['id'] ?? '') !== 'search_results') {
                continue;
            }
            foreach ($node['children'] as $child) {
                if (!empty($child['ctx'])) {
                    $contexts[] = $child['ctx'];
                }
            }
        }

        $this->assertSame(['unittestcg'], $contexts);
    }

    public function testResourceSortIgnoresContextGroupNodes()
    {
        $processor = new ResourceSort($this->modx);
        $formatNodes = new ReflectionMethod(ResourceSort::class, 'getNodesFormatted');
        $formatNodes->invoke($processor, [
            'cg-17' => [
                'web_0' => [
                    'web_123' => [],
                ],
            ],
        ]);

        $this->assertSame(['web'], $processor->contexts);
        $this->assertSame([[
            'id' => '123',
            'context' => 'web',
            'parent' => '0',
            'order' => 0,
        ]], $processor->nodes);
    }

    public function testContextGroupUpdateFromGrid()
    {
        $group = $this->modx->getObject(modContextGroup::class, ['name' => 'UnitTestGroup']);
        $this->assertInstanceOf(modContextGroup::class, $group);

        $result = $this->modx->runProcessor(UpdateFromGrid::class, [
            'data' => json_encode([
                'id' => $group->get('id'),
                'name' => 'UnitTestGroupFromGrid',
                'description' => 'Updated from grid',
                'rank' => 3,
            ]),
        ]);
        $this->assertTrue($this->checkForSuccess($result), $result->getMessage());
        $group = $this->modx->getObject(modContextGroup::class, ['id' => $group->get('id')]);
        $this->assertSame('UnitTestGroupFromGrid', $group->get('name'));
        $this->assertSame(3, (int)$group->get('rank'));
    }
}
