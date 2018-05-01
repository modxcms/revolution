<?php
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

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
    public $permission = 'view';
    
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->leftJoin('modContext', 'Context');
        
        $query = $this->getProperty('query');
        
        if (!empty($query)) {
            $c->where(array(
                'pagetitle:LIKE'    => '%' . $query . '%',
                'OR:longtitle:LIKE' => '%' . $query . '%'
            ));
        }
        
        $ignore = $this->getProperty('ignore');
        
        if (!empty($ignore)) {
            $c->where(array(
               'id:NOT IN' => explode(',', $ignore) 
            ));
        }
        
        $c->sortby('context_key');
        $c->sortby('pagetitle');
        
        return $c;
    }

    public function prepareQueryAfterCount(xPDOQuery $c) {
        $c->select($this->modx->getSelectColumns($this->classKey, $this->classKey));
        
        $c->select(array(
            'context_name' => 'Context.name'
        ));
        
        return $c;
    }
    
    public function beforeIteration(array $list) {
        if ($this->getProperty('combo', false) && !$this->getProperty('query', false)) {
            $list[] = array(
                'id'            => 0,
                'pagetitle'     => $this->modx->lexicon('parent_resource_empty'),
                'longtitle'     => '',
                'time'          => time()
            );
        }
        
        return $list;
    }

    public function prepareRow(xPDOObject $object) {
        $charset = $this->modx->getOption('modx_charset', null, 'UTF-8');
        
        return array(
            'id'            => $object->get('id'),
            'pagetitle'     => htmlentities($object->get('pagetitle'), ENT_COMPAT, $charset),
            'longtitle'     => htmlentities($object->get('longtitle'), ENT_COMPAT, $charset),
            'context_key'   => $object->get('context_key'),
            'context_name'  => $object->get('context_name'),
            'time'          => time()
        );
    }
}

return 'modResourceGetListProcessor';
