<?php
/**
 * Gets a list of active resources
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @param string $dateFormat (optional) The strftime date format to format the
 * editedon date to. Defaults to: %b %d, %Y %I:%M %p
 *
 * @package modx
 * @subpackage processors.system.activeresource
 */
class modActiveResourceListProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('view_document');
    } 
    public function getLanguageTopics() {
        return array('resource');
    }

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize() {
        $this->setDefaultProperties(array(
            'limit' => 20,
            'start' => 0,
            'sort' => 'editedon',
            'dir' => 'DESC',
            'dateFormat' => '%b %d, %Y %I:%M %p',
        ));
        return true;
    }

    /**
     * {@inheritDoc}
     * 
     * @return mixed
     */
    public function process() {
        $data = $this->getData();
        $list = $this->iterate($data['results']);
        return $this->outputArray($list,$data['total']);
    }

    /**
     * Get the collection of recently edited resources
     * @return array
     */
    public function getData() {
        $data = array();
        $limit = $this->getProperty('limit',20);
        $isLimit = !empty($limit);
        
        $c = $this->modx->newQuery('modResource');
        $c->innerJoin('modUser','EditedBy');
        $c->where(array(
            'deleted' => 0,
            'editedon:!=' => null,
        ));
        $data['total'] = $this->modx->getCount('modResource',$c);
        $c->select($this->modx->getSelectColumns('modResource','modResource'));
        $c->select($this->modx->getSelectColumns('modUser','EditedBy','',array('username')));
        $c->sortby($this->getProperty('sort'),$this->getProperty('dir'));
        if ($isLimit) $c->limit($limit,$this->getProperty('start',0));
        $data['results'] = $this->modx->getCollection('modResource',$c);
        
        return $data;
    }

    /**
     * Iterate across the resources and prepare them for outputting
     * @param array $resources
     * @return array
     */
    public function iterate(array $resources) {
        $list = array();
        $dateFormat = $this->getProperty('dateFormat');
        /** @var modResource $resource */
        foreach ($resources as $resource) {
            if (!$resource->checkPolicy('list')) continue;

            $resourceArray = $resource->get(array(
                'id','pagetitle','editedon','username',
            ));
            $resourceArray['editedon'] = strftime($dateFormat,strtotime($resource->get('editedon')));
            $list[] = $resourceArray;
        }
        return $list;
    }
}
return 'modActiveResourceListProcessor';