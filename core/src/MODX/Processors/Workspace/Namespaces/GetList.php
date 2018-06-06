<?php

namespace MODX\Processors\Workspace\Namespaces;

use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;
use MODX\Processors\modObjectGetListProcessor;

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
class GetList extends modObjectGetListProcessor
{
    public $classKey = 'modNamespace';
    public $languageTopics = ['namespace', 'workspace'];
    public $permission = 'namespaces';


    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'search' => false,
        ]);

        return $initialized;
    }


    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $search = $this->getProperty('search', '');
        if (!empty($search)) {
            $c->where([
                'name:LIKE' => '%' . $search . '%',
                'OR:path:LIKE' => '%' . $search . '%',
            ]);
        }

        return $c;
    }


    /**
     * Prepare the Namespace for listing
     *
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->toArray();
        $objectArray['perm'] = [];
        $objectArray['perm'][] = 'pedit';
        $objectArray['perm'][] = 'premove';

        return $objectArray;
    }
}