<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\Plugin;


use MODX\Revolution\modPlugin;

/**
 * Get a plugin
 *
 * @property integer $id The ID of the plugin
 *
 * @package MODX\Revolution\Processors\Element\Plugin
 */
class Get extends \MODX\Revolution\Processors\Element\Get
{
    public $classKey = modPlugin::class;
    public $languageTopics = ['plugin', 'category'];
    public $permission = 'view_plugin';
    public $objectType = 'plugin';
}
