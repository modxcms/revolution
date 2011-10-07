<?php
/**
 * Gets a list of resource groups associated to a TV.
 *
 * @param integer $tv The ID of the TV
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 20.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.element.template.tv.resourcegroup
 */
class modElementTvResourceGroupGetListProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('view_tv');
    }
    public function getLanguageTopics() {
        return array('tv');
    }
    public function initialize() {
        $this->setDefaultProperties(array(
            'start' => 0,
            'limit' => 20,
            'sort' => 'name',
            'dir' => 'ASC',
            'tv' => false,
        ));
        return true;
    }

    public function process() {
        $data = $this->getData();

        $list = array();
        /** @var modResourceGroup $resourceGroup */
        foreach ($data['results'] as $resourceGroup) {
            $resourceGroupArray = $this->prepareRow($resourceGroup);
            if (!empty($resourceGroupArray)) {
                $list[] = $resourceGroupArray;
            }
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
        $isLimit = !empty($limit);

        $c = $this->modx->newQuery('modResourceGroup');
        $data['total'] = $this->modx->getCount('modResourceGroup',$c);

        $c->sortby($this->getProperty('sort'),$this->getProperty('dir'));
        if ($isLimit) $c->limit($limit,$this->getProperty('start'));
        $data['results'] = $this->modx->getCollection('modResourceGroup',$c);

        return $data;
    }

    /**
     * Prepare object for iteration
     * 
     * @param modResourceGroup $resourceGroup
     * @return array
     */
    public function prepareRow(modResourceGroup $resourceGroup) {
        if ($this->getProperty('tv')) {
            $rgtv = $this->modx->getObject('modTemplateVarResourceGroup',array(
                'tmplvarid' => $this->getProperty('tv'),
                'documentgroup' => $resourceGroup->get('id'),
            ));
        } else $rgtv = null;
        
        $resourceGroupArray = $resourceGroup->toArray();
        $resourceGroupArray['access'] = $rgtv ? true : false;
        $resourceGroupArray['menu'] = array();
        return $resourceGroupArray;
    }
}
return 'modElementTvResourceGroupGetListProcessor';