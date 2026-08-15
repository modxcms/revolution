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

use MODX\Revolution\modContext;
use MODX\Revolution\modContextGroup;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Context\Group\Create;
use MODX\Revolution\Processors\Context\Group\GetList;
use MODX\Revolution\Processors\Context\Group\Remove;
use MODX\Revolution\Processors\Context\Group\Update;
use MODX\Revolution\Processors\ProcessorResponse;
use MODX\Revolution\Processors\Resource\GetNodes;

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
class ContextGroupProcessorsTest extends MODxTestCase
{
    /**
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();

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

        $contexts = $this->modx->getCollection(modContext::class, [
            'key:LIKE' => '%unittestcg%',
        ]);
        foreach ($contexts as $ctx) {
            $ctx->remove();
        }

        $groups = $this->modx->getCollection(modContextGroup::class, [
            'name:LIKE' => 'UnitTest%',
        ]);
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
}
