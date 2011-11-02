<?php
/**
 * Gets a list of resources.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @return array An array of modResources
 *
 * @package modx
 * @subpackage processors.resource
 */
class modResourceGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'modResource';
    public $languageTopics = array('resource');
    public $defaultSortField = 'pagetitle';

    public function prepareRow(xPDOObject $object) {
        $charset = $this->modx->getOption('modx_charset',null,'UTF-8');
        $objectArray = $object->toArray();
        $resourceArray['pagetitle'] = htmlentities($objectArray['pagetitle'],ENT_COMPAT,$charset);
        return $objectArray;
    }
}
return 'modResourceGetListProcessor';