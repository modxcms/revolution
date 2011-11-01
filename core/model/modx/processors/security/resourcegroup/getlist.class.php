<?php
/**
 * Gets a list of resource groups
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 *
 * @package modx
 * @subpackage processors.security.resourcegroup
 */
class modResourceGroupGetListProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('access_permissions');
    }
    public function getLanguageTopics() {
        return array('access');
    }
    
    public function initialize() {
        $this->setDefaultProperties(array(
            'start' => 0,
            'limit' => 10,
            'sort' => 'name',
            'dir' => 'ASC',
        ));
        return true;
    }

    public function process() {
        $data = $this->getData();

        $list = array();
        /** @var modResourceGroup $resourceGroup */
        foreach ($data['results'] as $resourceGroup) {
            $list[] = $resourceGroup->toArray();
        }
        return $this->outputArray($list,$data['total']);
    }

    /**
     * Get the Resource Group objects
     * @return array
     */
    public function getData() {
        $data = array();
        $limit = $this->getProperty('limit');

        $c = $this->modx->newQuery('modResourceGroup');
        $data['total'] = $this->modx->getCount('modResourceGroup',$c);

        $c->sortby($this->getProperty('sort'),$this->getProperty('dir'));
        if (intval($limit) > 0) {
            $c->limit($limit,$this->getProperty('start'));
        }
        $data['results'] = $this->modx->getCollection('modResourceGroup',$c);
        
        return $data;
    }
}
return 'modResourceGroupGetListProcessor';