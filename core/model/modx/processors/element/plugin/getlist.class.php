<?php
require_once (dirname(dirname(__FILE__)).'/getlist.class.php');
/**
 * Grabs a list of plugins.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.element.plugin
 */
class modPluginGetListProcessor extends modElementGetListProcessor {
    public $classKey = 'modPlugin';
    public $languageTopics = array('plugin','category');
}
return 'modPluginGetListProcessor';