<?php
require_once (dirname(dirname(__FILE__)).'/get.class.php');
/**
 * Get a plugin
 *
 * @param integer $id The ID of the plugin
 *
 * @package modx
 * @subpackage processors.element.plugin
 */
class modPluginGetProcessor extends modElementGetProcessor {
    public $classKey = 'modPlugin';
    public $languageTopics = array('plugin','category');
    public $permission = 'view_plugin';
    public $objectType = 'plugin';
}
return 'modPluginGetProcessor';