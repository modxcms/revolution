<?php
/**
 * Grabs a list of contexts.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 20.
 * @param string $sort (optional) The column to sort by. Defaults to key.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.context
 */
class modContextGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'modContext';
    public $permission = 'view_context';
    public $languageTopics = array('context');
    public $defaultSortField = 'key';
    /** @var boolean $canEdit Determines whether or not the user can edit a Context */
    public $canEdit = false;
    /** @var boolean $canRemove Determines whether or not the user can remove a Context */
    public $canRemove = false;

    public function initialize() {
        $initialized = parent::initialize();
        $this->setDefaultProperties(array(
            'search' => '',
            'exclude' => '',
        ));
        $this->canEdit = $this->modx->hasPermission('edit_context');
        $this->canRemove = $this->modx->hasPermission('delete_context');
        return $initialized;
    }

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $search = $this->getProperty('search');
        if (!empty($search)) {
            $c->where(array(
                'key:LIKE' => '%'.$search.'%',
                'OR:description:LIKE' => '%'.$search.'%',
            ));
        }
        $exclude = $this->getProperty('exclude');
        if (!empty($exclude)) {
            $c->where(array(
                'key:NOT IN' => is_string($exclude) ? explode(',',$exclude) : $exclude,
            ));
        }
        return $c;
    }

    /**
     * {@inheritDoc}
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object) {
        $contextArray = $object->toArray();
        if (!empty($contextArray['name'])) {
            $contextArray['name'] .= ' ';
        }
        $contextArray['name'] .= "({$contextArray['key']})";
        $contextArray['perm'] = array();
        if ($this->canEdit) {
            $contextArray['perm'][] = 'pedit';
        }
        if (!in_array($object->get('key'),array('mgr','web')) && $this->canRemove) {
            $contextArray['perm'][] = 'premove';
        }
        return $contextArray;
    }

}
return 'modContextGetListProcessor';
