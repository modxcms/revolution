<?php

namespace MODX\Processors\Element\Plugin;

/**
 * Delete a plugin.
 *
 * @param integer $id The ID of the plugin
 *
 * @package modx
 * @subpackage processors.element.plugin
 */
class Remove extends \MODX\Processors\Element\Remove
{
    public $classKey = 'modPlugin';
    public $languageTopics = ['plugin'];
    public $permission = 'delete_plugin';
    public $objectType = 'plugin';
    public $beforeRemoveEvent = 'OnBeforePluginFormDelete';
    public $afterRemoveEvent = 'OnPluginFormDelete';
}