<?php
/**
 * Gets a list of namespaces
 *
 * @param string $name (optional) If set, will search by name
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.workspace.namespace
 */
class modNamespaceGetListProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('namespaces');
    }
    public function getLanguageTopics() {
        return array('workspace','namespace');
    }

    public function initialize() {
        $this->setDefaultProperties(array(
            'limit' => 10,
            'start' => 0,
            'sort' => 'name',
            'dir' => 'ASC',
            'search' => false,
        ));
        return true;
    }
    public function process() {
        $data = $this->getData();
        if (empty($data)) return $this->failure();

        $list = array();
        foreach ($data['results'] as $namespace) {
            $namespaceArray = $this->prepareNamespace($namespace);
            if (!empty($namespaceArray)) {
                $list[] = $namespaceArray;
            }
        }
        return $this->outputArray($list,$data['total']);
    }

    /**
     * Get a collection of modNamespace objects
     * 
     * @return array
     */
    public function getData() {
        $data = array();
        $limit = $this->getProperty('limit');
        $isLimit = !empty($limit);

        $c = $this->modx->newQuery('modNamespace');

        $search = $this->getProperty('search');
        if (!empty($search)) {
            $c->where(array(
                'name:LIKE' => '%'.$search.'%',
                'OR:path:LIKE' => '%'.$search.'%',
            ));
        }
        $data['total'] = $this->modx->getCount('modNamespace',$c);

        $c->sortby($this->getProperty('sort'),$this->getProperty('dir'));
        if ($isLimit) $c->limit($limit,$this->getProperty('start'));

        /* get namespaces */
        $data['results'] = $this->modx->getCollection('modNamespace',$c);
        return $data;
    }

    /**
     * Prepare the Namespace for listing
     * 
     * @param modNamespace $namespace
     * @return array
     */
    public function prepareNamespace(modNamespace $namespace) {
        $namespaceArray = $namespace->toArray();
        $namespaceArray['perm'] = array();
        $namespaceArray['perm'][] = 'pedit';
        $namespaceArray['perm'][] = 'premove';
        return $namespaceArray;
    }
}
return 'modNamespaceGetListProcessor';