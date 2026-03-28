<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Tests\Controllers;

use MODX\Revolution\modManagerController;

/**
 * Minimal concrete controller to exercise invokeManagerPageInitEvents() in isolation.
 *
 * @internal
 */
final class MinimalManagerControllerForInitEventsStub extends modManagerController
{
    public function checkPermissions()
    {
        return true;
    }

    public function process(array $scriptProperties = [])
    {
        return [];
    }

    public function getPageTitle()
    {
        return '';
    }

    public function loadCustomCssJs()
    {
    }

    public function getTemplateFile()
    {
        return '';
    }

    /**
     * Test seam for protected invokeManagerPageInitEvents().
     *
     * @internal
     */
    public function runInvokeManagerPageInitEvents(): void
    {
        $this->invokeManagerPageInitEvents();
    }
}
