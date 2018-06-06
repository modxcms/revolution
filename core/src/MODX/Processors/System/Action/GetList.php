<?php

namespace MODX\Processors\System\Action;

use MODX\Processors\modObjectGetListProcessor;
use xPDO\Om\xPDOQuery;

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
class GetList extends modObjectGetListProcessor
{
    public $classKey = 'modAction';
    public $languageTopics = ['action', 'menu', 'namespace'];
    public $permission = 'actions';
    public $defaultSortField = 'controller';


    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'showNone' => true,
        ]);

        return $initialized;
    }


    public function beforeIteration(array $list)
    {
        if ($this->getProperty('showNone', false)) {
            $list[] = ['id' => 0, 'controller' => $this->modx->lexicon('action_none')];
        }

        return $list;
    }


    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->sortby($this->modx->getSelectColumns('modAction', 'modAction', '', ['namespace']), 'ASC');

        return $c;
    }
}
