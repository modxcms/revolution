<?php
/**
 * Grabs a list of actions
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to controller.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.system.action
 */
class modActionGetListProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('actions');
    }
    public function getLanguageTopics() {
        return array('action','menu','namespace');
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function initialize() {
        $this->setDefaultProperties(array(
            'limit' => 10,
            'start' => 0,
            'sort' => 'controller',
            'dir' => 'ASC',
            'showNone' => true,
        ));
        return true;
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function process() {
        $data = $this->getData();
        
        $list = array();
        if ($this->getProperty('showNone')) {
            $list[] = array('id' => 0, 'controller' => $this->modx->lexicon('action_none'));
        }
        /** @var modAction $action */
        foreach ($data['results'] as $action) {
            $list[] = $action->toArray();
        }
        return $this->outputArray($list,$data['total']);
    }

    /**
     * Get a collection of actions
     * @return array
     */
    public function getData() {
        $data = array();
        $limit = $this->getProperty('limit');
        $isLimit = !empty($limit);

        $c = $this->modx->newQuery('modAction');
        $data['total'] = $this->modx->getCount('modAction');
        
        $c->sortby($this->modx->getSelectColumns('modAction','modAction','',array('namespace')),'ASC');
        $c->sortby($this->getProperty('sort'),$this->getProperty('dir'));
        if ($isLimit) {
            $c->limit($limit,$this->getProperty('start'));
        }
        $data['results'] = $this->modx->getIterator('modAction',$c);
        return $data;
    }
}
return 'modActionGetListProcessor';