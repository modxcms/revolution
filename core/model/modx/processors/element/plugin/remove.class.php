<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

require_once (dirname(__DIR__).'/remove.class.php');
/**
 * Delete a plugin.
 *
 * @param integer $id The ID of the plugin
 *
 * @package modx
 * @subpackage processors.element.plugin
 */
class modPluginRemoveProcessor extends modElementRemoveProcessor {
    public $classKey = 'modPlugin';
    public $languageTopics = array('plugin');
    public $permission = 'delete_plugin';
    public $objectType = 'plugin';
    public $beforeRemoveEvent = 'OnBeforePluginFormDelete';
    public $afterRemoveEvent = 'OnPluginFormDelete';
}
return 'modPluginRemoveProcessor';
