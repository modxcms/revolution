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

namespace MODX\Revolution\Tests\Processors\Resource;

use MODX\Revolution\modDocument;
use MODX\Revolution\modResource;
use MODX\Revolution\modX;
use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Resource\Create;
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
            $this->assertSame(1, (int)$resource->get('deleted'), $resource->get('pagetitle') . ' should be marked deleted');
            $this->assertGreaterThan(0, (int)$resource->get('deletedon'), $resource->get('pagetitle') . ' should have deletedon set');
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
