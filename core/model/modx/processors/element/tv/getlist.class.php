<?php
require_once (dirname(dirname(__FILE__)).'/getlist.class.php');
/**
 * Grabs a list of TVs.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.element.tv
 */
class modTemplateVarGetListProcessor extends modElementGetListProcessor {
    public $classKey = 'modTemplateVar';
    public $languageTopics = array('tv','category');
}
return 'modTemplateVarGetListProcessor';