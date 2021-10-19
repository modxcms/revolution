<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\TemplateVar\Configs\Controllers;

use MODX\Revolution\modManagerController;

/**
 * Simulate the TV manager controller to get TV input renders
 *
 * @package MODX\Revolution\Processors\Element\TemplateVar\Configs\Controllers
 */
class TvInputManagerController extends modManagerController
{
    public $loadFooter = false;
    public $loadHeader = false;
    public function checkPermissions()
    {
        return $this->modx->hasPermission('view_tv');
    }
    public function loadCustomCssJs()
    {
    }
    public function process(array $scriptProperties = [])
    {
    }
    public function getPageTitle()
    {
        return '';
    }
    public function getTemplateFile()
    {
        return 'empty.tpl';
    }
    public function getLanguageTopics()
    {
        return [];
    }
}
