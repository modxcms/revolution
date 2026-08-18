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
use MODX\Revolution\modWebLink;
use MODX\Revolution\modX;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Resource\Create;
use MODX\Revolution\Processors\Resource\Delete;
use MODX\Revolution\Processors\Resource\Update;

/**
 * Form "Deleted" checkbox must cascade like context-menu delete (#14167).
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Resource
 * @group ResourceProcessors
 * @group modResource
 */
class ResourceUpdateDeleteCascadeTest extends MODxTestCase
{
    private const TITLE_PREFIX = 'Unit Test Resource 14167';

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

    public function testFormDeleteMarksChildrenDeleted()
    {
        $parent = $this->createTestResource(self::TITLE_PREFIX . ' Parent');
        $child = $this->createTestResource(self::TITLE_PREFIX . ' Child', $parent->get('id'));
        $grandchild = $this->createTestResource(self::TITLE_PREFIX . ' Grandchild', $child->get('id'));

        $this->saveDeletedCheckbox($parent, 1);

        foreach ([$parent, $child, $grandchild] as $created) {
            $resource = $this->modx->getObject(modResource::class, $created->get('id'));
            $this->assertNotEmpty($resource, 'Resource ' . $created->get('pagetitle') . ' missing after form delete');
            $this->assertSame(
                1,
                (int)$resource->get('deleted'),
                $resource->get('pagetitle') . ' should be marked deleted'
            );
            $this->assertGreaterThan(
                0,
                (int)$resource->get('deletedon'),
                $resource->get('pagetitle') . ' should have deletedon set'
            );
            $this->assertGreaterThan(
                0,
                (int)$resource->get('deletedby'),
                $resource->get('pagetitle') . ' should record deletedby'
            );
        }
    }

    public function testFormUndeleteRestoresChildren()
    {
        $parent = $this->createTestResource(self::TITLE_PREFIX . ' Restore Parent');
        $child = $this->createTestResource(self::TITLE_PREFIX . ' Restore Child', $parent->get('id'));

        $this->saveDeletedCheckbox($parent, 1);

        $parent = $this->modx->getObject(modResource::class, $parent->get('id'));
        $child = $this->modx->getObject(modResource::class, $child->get('id'));
        $this->assertNotEmpty($parent);
        $this->assertNotEmpty($child);
        $this->assertSame(1, (int)$parent->get('deleted'));
        $this->assertSame(1, (int)$child->get('deleted'));

        $this->saveDeletedCheckbox($parent, 0);

        $parent = $this->modx->getObject(modResource::class, $parent->get('id'));
        $child = $this->modx->getObject(modResource::class, $child->get('id'));
        $this->assertSame(0, (int)$parent->get('deleted'));
        $this->assertSame(0, (int)$child->get('deleted'));
        $this->assertSame(0, (int)$parent->get('deletedon'));
        $this->assertSame(0, (int)$child->get('deletedon'));
        $this->assertSame(0, (int)$parent->get('deletedby'));
        $this->assertSame(0, (int)$child->get('deletedby'));
    }

    public function testFormSaveWithoutChangingDeletedLeavesResourceLive()
    {
        $resource = $this->createTestResource(self::TITLE_PREFIX . ' Unchanged');
        $this->modx->error->reset();
        $fields = $resource->toArray();
        $fields['deleted'] = 0;
        $fields['syncsite'] = 0;
        $fields['pagetitle'] = self::TITLE_PREFIX . ' Unchanged Saved';
        $result = $this->modx->runProcessor(Update::class, $fields);
        $this->assertTrue($this->checkForSuccess($result), $result ? $result->getMessage() : 'no response');

        $resource = $this->modx->getObject(modResource::class, $resource->get('id'));
        $this->assertSame(0, (int)$resource->get('deleted'));
    }

    public function testCannotMoveResourceUnderDeletedParent()
    {
        $deletedParent = $this->createTestResource(self::TITLE_PREFIX . ' Deleted Target');
        $moving = $this->createTestResource(self::TITLE_PREFIX . ' Moving');

        $this->modx->error->reset();
        $delete = $this->modx->runProcessor(Delete::class, [
            'id' => $deletedParent->get('id'),
            'syncsite' => 0,
        ]);
        $this->assertTrue($this->checkForSuccess($delete), $delete ? $delete->getMessage() : 'delete failed');

        $this->modx->error->reset();
        $fields = $moving->toArray();
        $fields['parent'] = $deletedParent->get('id');
        $fields['syncsite'] = 0;
        $result = $this->modx->runProcessor(Update::class, $fields);
        $this->assertFalse($this->checkForSuccess($result), 'Move under a deleted parent must fail');
    }

    public function testFormDeleteFailsWhenChildIsSiteStart()
    {
        $parent = $this->createTestResource(self::TITLE_PREFIX . ' Protected Parent');
        $child = $this->createTestResource(self::TITLE_PREFIX . ' Protected Child', $parent->get('id'));
        $previous = $this->modx->getOption('site_start');
        $this->modx->setOption('site_start', $child->get('id'));

        $this->modx->error->reset();
        $fields = $parent->toArray();
        $fields['deleted'] = 1;
        $fields['syncsite'] = 0;
        $result = $this->modx->runProcessor(Update::class, $fields);
        $this->modx->setOption('site_start', $previous);

        $this->assertFalse($this->checkForSuccess($result), 'Form delete of a site_start container must fail');
        $parent = $this->modx->getObject(modResource::class, $parent->get('id'));
        $child = $this->modx->getObject(modResource::class, $child->get('id'));
        $this->assertSame(0, (int)$parent->get('deleted'));
        $this->assertSame(0, (int)$child->get('deleted'));
    }

    public function testFormDeleteMarksWebLinkDeleted()
    {
        $weblink = $this->createTestResource(self::TITLE_PREFIX . ' WebLink', 0, [
            'class_key' => modWebLink::class,
            'content' => 'https://example.com',
        ]);

        $this->saveDeletedCheckbox($weblink, 1);

        $weblink = $this->modx->getObject(modResource::class, $weblink->get('id'));
        $this->assertSame(1, (int)$weblink->get('deleted'));
        $this->assertGreaterThan(0, (int)$weblink->get('deletedon'));
    }

    private function saveDeletedCheckbox(modResource $resource, int $deleted): void
    {
        $this->modx->error->reset();
        $fields = $resource->toArray();
        $fields['deleted'] = $deleted;
        $fields['syncsite'] = 0;
        $result = $this->modx->runProcessor(Update::class, $fields);
        $this->assertTrue(
            $this->checkForSuccess($result),
            $result ? $result->getMessage() : 'no response'
        );
    }

    private function createTestResource(string $pagetitle, int $parent = 0, array $extra = []): modResource
    {
        $this->modx->error->reset();
        $result = $this->modx->runProcessor(Create::class, array_merge([
            'pagetitle' => $pagetitle,
            'alias' => strtolower(str_replace(' ', '-', $pagetitle)),
            'parent' => $parent,
            'template' => 0,
            'published' => false,
            'context_key' => 'web',
            'class_key' => modDocument::class,
        ], $extra));
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
