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
 * Minimal concrete controller to exercise manager page init events.
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
     * Mirrors the init event block at the start of modManagerController::render().
     *
     * @internal
     */
    public function runPageInitEventsForTest(): void
    {
        if (!$this->checkPermissions()) {
            return;
        }

        $this->modx->invokeEvent('OnBeforeManagerPageInit', $this->config);

        $request = $this->modx->request;
        $this->modx->invokeEvent('OnManagerPageInit', array_merge($this->config, [
            'action' => $request->action,
            'namespace' => $request->namespace,
        ]));
    }
}
