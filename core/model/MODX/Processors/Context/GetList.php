<?php

namespace MODX\Processors\Context;

use MODX\Processors\modObjectGetListProcessor;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

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
class GetList extends modObjectGetListProcessor
{
    public $classKey = 'modContext';
    public $permission = 'view_context';
    public $languageTopics = ['context'];
    public $defaultSortField = 'key';
    /** @var boolean $canEdit Determines whether or not the user can edit a Context */
    public $canEdit = false;
    /** @var boolean $canRemove Determines whether or not the user can remove a Context */
    public $canRemove = false;
    /** @var boolean $canCreate Determines whether or not the user can create a context (/duplicate one) */
    public $canCreate = false;


    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'search' => '',
            'exclude' => '',
        ]);
        $this->canCreate = $this->modx->hasPermission('new_context');
        $this->canEdit = $this->modx->hasPermission('edit_context');
        $this->canRemove = $this->modx->hasPermission('delete_context');

        return $initialized;
    }


    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $search = $this->getProperty('search');
        if (!empty($search)) {
            $c->where([
                'key:LIKE' => '%' . $search . '%',
                'OR:description:LIKE' => '%' . $search . '%',
            ]);
        }
        $exclude = $this->getProperty('exclude');
        if (!empty($exclude)) {
            $c->where([
                'key:NOT IN' => is_string($exclude) ? explode(',', $exclude) : $exclude,
            ]);
        }

        return $c;
    }


    /**
     * {@inheritDoc}
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $contextArray = $object->toArray();
        $contextArray['perm'] = [];
        if ($this->canCreate) {
            $contextArray['perm'][] = 'pnew';
        }
        if ($this->canEdit) {
            $contextArray['perm'][] = 'pedit';
        }
        if (!in_array($object->get('key'), ['mgr', 'web']) && $this->canRemove) {
            $contextArray['perm'][] = 'premove';
        }

        return $contextArray;
    }

}
