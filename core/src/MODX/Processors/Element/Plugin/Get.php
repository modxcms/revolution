<?php

namespace MODX\Processors\Element\Plugin;

/**
 * Get a plugin
 *
 * @param integer $id The ID of the plugin
 *
 * @package modx
 * @subpackage processors.element.plugin
 */
class Get extends \MODX\Processors\Element\Get
{
    public $classKey = 'modPlugin';
    public $languageTopics = ['plugin', 'category'];
    public $permission = 'view_plugin';
    public $objectType = 'plugin';
}