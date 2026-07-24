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
namespace MODX\Revolution\Tests\Processors\Workspace\Packages;

use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\ProcessorResponse;
use MODX\Revolution\Processors\Workspace\Packages\GetList;

/**
 * Tests for Workspace Packages GetList processor.
 *
 * @package modx-test
 * @subpackage modx
 * @group Processors
 * @group Workspace
 * @group Packages
 */
class GetListTest extends MODxTestCase
{
    /**
     * Test GetList processor without filter.
     */
    public function testGetListWithoutFilter()
    {
        $result = $this->modx->runProcessor(GetList::class, [
            'workspace' => 1,
            'limit' => 10,
            'start' => 0,
        ]);
        if (empty($result)) {
            $this->fail('Could not load ' . GetList::class . ' processor');
        }
        $this->assertInstanceOf(ProcessorResponse::class, $result);
        $this->assertFalse($result->isError(), 'GetList failed: ' . $result->getMessage());
    }

    /**
     * Test GetList processor with filter=uninstalled.
     */
    public function testGetListWithFilterUninstalled()
    {
        $result = $this->modx->runProcessor(GetList::class, [
            'workspace' => 1,
            'limit' => 10,
            'start' => 0,
            'filter' => 'uninstalled',
        ]);
        if (empty($result)) {
            $this->fail('Could not load ' . GetList::class . ' processor');
        }
        $this->assertInstanceOf(ProcessorResponse::class, $result);
        $this->assertFalse($result->isError(), 'GetList with filter=uninstalled failed: ' . $result->getMessage());
    }
}
