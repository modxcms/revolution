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
class modNamespaceGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'modNamespace';
    public $languageTopics = array('namespace','workspace');
    public $permission = 'namespaces';

    public function initialize() {
        $initialized = parent::initialize();
        $this->setDefaultProperties(array(
            'search' => false,
        ));
        return $initialized;
    }

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $search = $this->getProperty('search','');
        if (!empty($search)) {
            $c->where(array(
                'name:LIKE' => '%'.$search.'%',
                'OR:path:LIKE' => '%'.$search.'%',
            ));
        }
        return $c;
    }

    /**
     * Prepare the Namespace for listing
     * 
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object) {
        $objectArray = $object->toArray();
        $objectArray['perm'] = array();
        $objectArray['perm'][] = 'pedit';
        $objectArray['perm'][] = 'premove';
        return $objectArray;
    }
}
return 'modNamespaceGetListProcessor';