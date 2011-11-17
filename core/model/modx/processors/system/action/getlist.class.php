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
class modActionGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'modAction';
    public $languageTopics = array('action','menu','namespace');
    public $permission = 'actions';
    public $defaultSortField = 'controller';

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function initialize() {
        $initialized = parent::initialize();
        $this->setDefaultProperties(array(
            'showNone' => true,
        ));
        return $initialized;
    }

    public function beforeIteration(array $list) {
        if ($this->getProperty('showNone',false)) {
            $list[] = array('id' => 0, 'controller' => $this->modx->lexicon('action_none'));
        }
        return $list;
    }

    public function prepareQueryAfterCount(xPDOQuery $c) {
        $c->sortby($this->modx->getSelectColumns('modAction','modAction','',array('namespace')),'ASC');
        return $c;
    }
}
return 'modActionGetListProcessor';