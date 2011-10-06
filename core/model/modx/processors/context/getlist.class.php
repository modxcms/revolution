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
class modContextGetListProcessor extends modProcessor {
    /** @var boolean $canEdit Determines whether or not the user can edit a Context */
    public $canEdit = false;
    /** @var boolean $canRemove Determines whether or not the user can remove a Context */
    public $canRemove = false;

    public function checkPermissions() {
        return $this->modx->hasPermission('view_context');
    }

    public function getLanguageTopics() {
        return array('context');
    }

    public function initialize() {
        $this->setDefaultProperties(array(
            'start' => 0,
            'limit' => 10,
            'sort' => 'key',
            'dir' => 'ASC',
            'search' => '',
            'exclude' => '',
        ));
        $this->canEdit = $this->modx->hasPermission('edit_context');
        $this->canRemove = $this->modx->hasPermission('delete_context');
        return true;
    }

    /**
     * {@inheritDoc}
     * 
     * @return string
     */
    public function process() {
        $data = $this->getContexts();
        if (empty($data)) return $this->failure();

        /* iterate through contexts */
        $list = $this->iterate($data['results']);
        return $this->outputArray($list,$data['total']);
    }

    /**
     * Get a response array of contexts for iteration
     * 
     * @return array
     */
    public function getContexts() {
        $response = array();
        $limit = $this->getProperty('limit',10);
        $isLimit = !empty($limit);

        /* query contexts */
        $c = $this->modx->newQuery('modContext');
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
        $response['total'] = $this->modx->getCount('modContext',$c);

        $c->sortby($this->modx->getSelectColumns('modContext','modContext','',array($this->getProperty('sort','key'))),$this->getProperty('dir','ASC'));
        if ($isLimit) {
            $c->limit($limit,$this->getProperty('start',0));
        }
        $response['results'] = $this->modx->getCollection('modContext',$c);
        return $response;
    }

    /**
     * Iterate across the contexts
     * 
     * @param array $contexts
     * @return array
     */
    public function iterate(array $contexts = array()) {
        $list = array();
        /** @var modContext $context */
        foreach ($contexts as $context) {
            if (!$context->checkPolicy('list')) continue;
            $list[] = $this->prepareContext($context);
        }
        return $list;
    }

    /**
     * Prepare a context for listing
     * 
     * @param modContext $context
     * @return array
     */
    public function prepareContext(modContext $context) {
        $contextArray = $context->toArray();
        $contextArray['perm'] = array();
        if ($this->canEdit) {
            $contextArray['perm'][] = 'pedit';
        }
        if (!in_array($context->get('key'),array('mgr','web')) && $this->canRemove) {
            $contextArray['perm'][] = 'premove';
        }
        return $contextArray;
    }

}
return 'modContextGetListProcessor';