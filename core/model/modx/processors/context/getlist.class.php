<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

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
    /** @var boolean $canCreate Determines whether or not the user can create a context (/duplicate one) */
    public $canCreate = false;

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize() {
        $initialized = parent::initialize();
        $this->setDefaultProperties(array(
            'search' => '',
            'exclude' => '',
        ));
        $this->canCreate = $this->modx->hasPermission('new_context');
        $this->canEdit = $this->modx->hasPermission('edit_context');
        $this->canRemove = $this->modx->hasPermission('delete_context');
        return $initialized;
    }

    /**
     * {@inheritDoc}
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
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
     * Filter the query by the valueField of MODx.combo.Context to get the initially value displayed right
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c) {
        $key = $this->getProperty('key','');
        if (!empty($key)) {
            $c->where(array(
                $this->classKey . '.key:IN' => is_string($key) ? explode(',', $key) : $key,
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
        $contextArray['perm'] = array();
        if ($this->canCreate) {
            $contextArray['perm'][] = 'pnew';
        }
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
