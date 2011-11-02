<?php
/**
 * Retrieves a resource by its ID.
 *
 * @param integer $id The ID of the resource to grab
 * @return modResource
 *
 * @package modx
 * @subpackage processors.resource
 */
class modResourceGetProcessor extends modObjectGetProcessor {
    public $classKey = 'modResource';
    public $languageTopics = array('resource');
    public $objectType = 'resource';

    public function process() {
        $resourceArray = $this->object->toArray();
        $this->formatDates($resourceArray);
        return $this->success('',$resourceArray);
    }

    public function formatDates(array &$resourceArray) {
        if (!empty($resourceArray['pub_date']) && $resourceArray['pub_date'] != '0000-00-00 00:00:00') {
            $resourceArray['pub_date'] = strftime('%Y-%m-%d %H:%M:%S',strtotime($resourceArray['pub_date']));
        } else $resourceArray['pub_date'] = '';
        if (!empty($resourceArray['unpub_date']) && $resourceArray['unpub_date'] != '0000-00-00 00:00:00') {
            $resourceArray['unpub_date'] = strftime('%Y-%m-%d %H:%M:%S',strtotime($resourceArray['unpub_date']));
        } else $resourceArray['unpub_date'] = '';
        if (!empty($resourceArray) && $resourceArray['publishedon'] != '0000-00-00 00:00:00') {
            $resourceArray['publishedon'] = strftime('%Y-%m-%d %H:%M:%S',strtotime($resourceArray['publishedon']));
        } else $resourceArray['publishedon'] = '';
    }
}
return 'modResourceGetProcessor';