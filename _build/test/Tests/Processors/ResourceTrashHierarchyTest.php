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

namespace MODX\Revolution\Tests\Processors;

use MODX\Revolution\modDocument;
use MODX\Revolution\modResource;
use MODX\Revolution\modX;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Resource\Create;
use MODX\Revolution\Processors\Resource\Delete;
use MODX\Revolution\Processors\Resource\Trash\Purge;
use MODX\Revolution\Processors\Resource\Trash\Restore;
use MODX\Revolution\Processors\Resource\Undelete;

/**
 * Trash restore of a child restores deleted ancestors; purge lists descendants;
 * parent delete is blocked when the tree contains a system page (#14167).
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Resource
 * @group ResourceProcessors
 * @group modResource
 */
class ResourceTrashHierarchyTest extends MODxTestCase
{
    private const TITLE_PREFIX = 'Unit Test Resource 14167 hierarchy';

    /**
     * @before
     */
    public function setUpFixtures()
    {
        parent::setUpFixtures();
        $this->modx->eventMap = [];
        $this->removeTestResources();
    }

    /**
     * @after
     */
    public function tearDownFixtures()
    {
        $this->removeTestResources();
        parent::tearDownFixtures();
    }

    public function testTrashRestoreChildAlsoRestoresDeletedParent()
    {
        $parent = $this->createTestResource(self::TITLE_PREFIX . ' Restore Parent');
        $child = $this->createTestResource(self::TITLE_PREFIX . ' Restore Child', $parent->get('id'));

        $this->modx->error->reset();
        $delete = $this->modx->runProcessor(Delete::class, [
            'id' => $parent->get('id'),
            'syncsite' => 0,
        ]);
        $this->assertTrue($this->checkForSuccess($delete), $delete ? $delete->getMessage() : 'delete failed');

        $this->modx->error->reset();
        $restore = $this->modx->runProcessor(Restore::class, [
            'ids' => (string)$child->get('id'),
        ]);
        $this->assertTrue($this->checkForSuccess($restore), $restore ? $restore->getMessage() : 'restore failed');

        $parent = $this->modx->getObject(modResource::class, $parent->get('id'));
        $child = $this->modx->getObject(modResource::class, $child->get('id'));
        $this->assertSame(0, (int)$parent->get('deleted'), 'deleted parent must be restored with the child');
        $this->assertSame(0, (int)$child->get('deleted'));
    }

    public function testPurgeParentMessageIncludesChildren()
    {
        $parent = $this->createTestResource(self::TITLE_PREFIX . ' Purge Parent');
        $child = $this->createTestResource(self::TITLE_PREFIX . ' Purge Child', $parent->get('id'));
        $parentId = $parent->get('id');
        $childId = $child->get('id');

        $this->modx->error->reset();
        $delete = $this->modx->runProcessor(Delete::class, [
            'id' => $parentId,
            'syncsite' => 0,
        ]);
        $this->assertTrue($this->checkForSuccess($delete), $delete ? $delete->getMessage() : 'delete failed');

        $this->modx->error->reset();
        $purge = $this->modx->runProcessor(Purge::class, [
            'ids' => (string)$parentId,
        ]);
        $this->assertTrue($this->checkForSuccess($purge), $purge ? $purge->getMessage() : 'purge failed');
        $object = $purge->getObject();
        $this->assertGreaterThanOrEqual(2, (int)$object['count_success']);
        $this->assertEmpty($this->modx->getObject(modResource::class, $parentId));
        $this->assertEmpty($this->modx->getObject(modResource::class, $childId));
    }

    public function testDeleteParentContainingSiteStartIsBlocked()
    {
        $parent = $this->createTestResource(self::TITLE_PREFIX . ' Protected Parent');
        $child = $this->createTestResource(self::TITLE_PREFIX . ' Protected Child', $parent->get('id'));
        $previous = $this->modx->getOption('site_start');
        $this->modx->setOption('site_start', $child->get('id'));

        $this->modx->error->reset();
        $delete = $this->modx->runProcessor(Delete::class, [
            'id' => $parent->get('id'),
            'syncsite' => 0,
        ]);
        $this->modx->setOption('site_start', $previous);

        $this->assertFalse($this->checkForSuccess($delete), 'Deleting a container of site_start must fail');
        $parent = $this->modx->getObject(modResource::class, $parent->get('id'));
        $child = $this->modx->getObject(modResource::class, $child->get('id'));
        $this->assertSame(0, (int)$parent->get('deleted'));
        $this->assertSame(0, (int)$child->get('deleted'));
    }

    public function testCannotCreateUnderDeletedParent()
    {
        $parent = $this->createTestResource(self::TITLE_PREFIX . ' Deleted Parent');
        $this->modx->error->reset();
        $delete = $this->modx->runProcessor(Delete::class, [
            'id' => $parent->get('id'),
            'syncsite' => 0,
        ]);
        $this->assertTrue($this->checkForSuccess($delete), $delete ? $delete->getMessage() : 'delete failed');

        $this->modx->error->reset();
        $create = $this->modx->runProcessor(Create::class, [
            'pagetitle' => self::TITLE_PREFIX . ' Under Deleted',
            'alias' => 'unit-test-14167-under-deleted',
            'parent' => $parent->get('id'),
            'template' => 0,
            'published' => false,
            'context_key' => 'web',
            'class_key' => modDocument::class,
        ]);
        $this->assertFalse($this->checkForSuccess($create), 'Create under a deleted parent must fail');
    }

    public function testCannotDeleteSiteStartResource()
    {
        $resource = $this->createTestResource(self::TITLE_PREFIX . ' Site Start');
        $previous = $this->modx->getOption('site_start');
        $this->modx->setOption('site_start', $resource->get('id'));

        $this->modx->error->reset();
        $delete = $this->modx->runProcessor(Delete::class, [
            'id' => $resource->get('id'),
            'syncsite' => 0,
        ]);
        $this->modx->setOption('site_start', $previous);

        $this->assertFalse($this->checkForSuccess($delete), 'Deleting site_start itself must fail');
        $resource = $this->modx->getObject(modResource::class, $resource->get('id'));
        $this->assertSame(0, (int)$resource->get('deleted'));
    }

    public function testDeleteParentContainingErrorPageIsBlocked()
    {
        $parent = $this->createTestResource(self::TITLE_PREFIX . ' Error Parent');
        $child = $this->createTestResource(self::TITLE_PREFIX . ' Error Child', $parent->get('id'));
        $previous = $this->modx->getOption('error_page');
        $this->modx->setOption('error_page', $child->get('id'));

        $this->modx->error->reset();
        $delete = $this->modx->runProcessor(Delete::class, [
            'id' => $parent->get('id'),
            'syncsite' => 0,
        ]);
        $this->modx->setOption('error_page', $previous);

        $this->assertFalse($this->checkForSuccess($delete), 'Deleting a container of error_page must fail');
        $parent = $this->modx->getObject(modResource::class, $parent->get('id'));
        $this->assertSame(0, (int)$parent->get('deleted'));
    }

    public function testPurgeWithParentAndChildIdsCountsEachOnce()
    {
        $parent = $this->createTestResource(self::TITLE_PREFIX . ' Dual Purge Parent');
        $child = $this->createTestResource(self::TITLE_PREFIX . ' Dual Purge Child', $parent->get('id'));
        $parentId = $parent->get('id');
        $childId = $child->get('id');

        $this->modx->error->reset();
        $delete = $this->modx->runProcessor(Delete::class, [
            'id' => $parentId,
            'syncsite' => 0,
        ]);
        $this->assertTrue($this->checkForSuccess($delete), $delete ? $delete->getMessage() : 'delete failed');

        $this->modx->error->reset();
        $purge = $this->modx->runProcessor(Purge::class, [
            'ids' => $parentId . ',' . $childId,
        ]);
        $this->assertTrue($this->checkForSuccess($purge), $purge ? $purge->getMessage() : 'purge failed');
        $object = $purge->getObject();
        $this->assertGreaterThanOrEqual(2, (int)$object['count_success']);
        $this->assertEmpty($this->modx->getObject(modResource::class, $parentId));
        $this->assertEmpty($this->modx->getObject(modResource::class, $childId));
    }

    public function testUndeleteProcessorRestoresDeletedParent()
    {
        $parent = $this->createTestResource(self::TITLE_PREFIX . ' Undelete Parent');
        $child = $this->createTestResource(self::TITLE_PREFIX . ' Undelete Child', $parent->get('id'));

        $this->modx->error->reset();
        $delete = $this->modx->runProcessor(Delete::class, [
            'id' => $parent->get('id'),
            'syncsite' => 0,
        ]);
        $this->assertTrue($this->checkForSuccess($delete), $delete ? $delete->getMessage() : 'delete failed');

        $this->modx->error->reset();
        $undelete = $this->modx->runProcessor(Undelete::class, [
            'id' => $child->get('id'),
            'syncsite' => 0,
        ]);
        $this->assertTrue($this->checkForSuccess($undelete), $undelete ? $undelete->getMessage() : 'undelete failed');

        $parent = $this->modx->getObject(modResource::class, $parent->get('id'));
        $child = $this->modx->getObject(modResource::class, $child->get('id'));
        $this->assertSame(0, (int)$parent->get('deleted'));
        $this->assertSame(0, (int)$child->get('deleted'));
    }

    public function testRestoreChildWithMissingParentStillSucceeds()
    {
        $parent = $this->createTestResource(self::TITLE_PREFIX . ' Ghost Parent');
        $child = $this->createTestResource(self::TITLE_PREFIX . ' Ghost Child', $parent->get('id'));

        $this->modx->error->reset();
        $delete = $this->modx->runProcessor(Delete::class, [
            'id' => $parent->get('id'),
            'syncsite' => 0,
        ]);
        $this->assertTrue($this->checkForSuccess($delete), $delete ? $delete->getMessage() : 'delete failed');

        $child = $this->modx->getObject(modResource::class, $child->get('id'));
        $child->set('parent', 2147483646);
        $child->save();

        $this->modx->error->reset();
        $restore = $this->modx->runProcessor(Restore::class, [
            'ids' => (string)$child->get('id'),
        ]);
        $this->assertTrue($this->checkForSuccess($restore), $restore ? $restore->getMessage() : 'restore failed');

        $child = $this->modx->getObject(modResource::class, $child->get('id'));
        $this->assertSame(0, (int)$child->get('deleted'));
    }

    private function createTestResource(string $pagetitle, int $parent = 0): modResource
    {
        $this->modx->error->reset();
        $result = $this->modx->runProcessor(Create::class, [
            'pagetitle' => $pagetitle,
            'alias' => strtolower(str_replace(' ', '-', $pagetitle)),
            'parent' => $parent,
            'template' => 0,
            'published' => false,
            'context_key' => 'web',
            'class_key' => modDocument::class,
        ]);
        $this->assertTrue(
            $this->checkForSuccess($result),
            'Could not create ' . $pagetitle . ': ' . ($result ? $result->getMessage() : 'no response')
        );

        $resource = $this->modx->getObject(modResource::class, ['pagetitle' => $pagetitle]);
        $this->assertNotEmpty($resource, 'Created resource not found: ' . $pagetitle);

        return $resource;
    }

    private function removeTestResources(): void
    {
        if (!($this->modx instanceof modX)) {
            return;
        }
        $resources = $this->modx->getCollection(modResource::class, [
            'pagetitle:LIKE' => '%' . self::TITLE_PREFIX . '%',
        ]);
        foreach ($resources as $resource) {
            $resource->remove();
        }
    }
}
