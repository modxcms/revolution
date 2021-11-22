<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Context;

use MODX\Revolution\modContext;
use MODX\Revolution\Processors\Model\GetListProcessor;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Grabs a list of contexts.
 *
 * @property integer $start (optional) The record to start at. Defaults to 0.
 * @property integer $limit (optional) The number of records to limit to. Defaults
 * to 20.
 * @property string  $sort  (optional) The column to sort by. Defaults to key.
 * @property string  $dir   (optional) The direction of the sort. Defaults to ASC.
 *
 * @package MODX\Revolution\Processors\Context
 */
class GetList extends GetListProcessor
{
    public $classKey = modContext::class;
    public $permission = 'view_context';
    public $languageTopics = ['context'];
    public $defaultSortField = 'key';
    /** @var boolean $canCreate Determines whether or not the user can create a context (/duplicate one) */
    protected $canCreate = false;
    /** @var boolean $canUpdate Determines whether or not the user can edit a Context */
    protected $canUpdate = false;
    /** @var boolean $canDelete Determines whether or not the user can remove a Context */
    protected $canDelete = false;

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'search' => '',
            'exclude' => 'creator',
        ]);

        $this->canCreate = $this->modx->hasPermission('new_context');
        $this->canUpdate = $this->modx->hasPermission('edit_context');
        $this->canDelete = $this->modx->hasPermission('delete_context');

        return $initialized;
    }

    /**
     * {@inheritDoc}
     * @return boolean
     */
    public function beforeQuery()
    {
        /*
            Implementing a little trick here since 'creator' is an arbitrary field
            not present in the database, used for distinguishing core/protected row data
            from user-created data
        */
        if ($this->getProperty('sort') === 'creator') {
            $this->setProperty('sort', 'FIELD(modContext.key, "mgr", "web")');
            $dir = $this->getProperty('dir') === 'ASC' ? 'DESC' : 'ASC' ;
            $this->setProperty('dir', $dir);
        }
        return true;
    }

    /**
     * {@inheritDoc}
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        // $msg = "\r\n prepareQueryBeforeCount, \$properties:\r\n" . print_r($this->getProperties(), true);
        // $this->modx->log(\modX::LOG_LEVEL_ERROR, $msg, '', __CLASS__);

        $search = $this->getProperty('search');
        if (!empty($search)) {
            $c->where([
                'key:LIKE' => '%' . $search . '%',
                'OR:name:LIKE' => '%' . $search . '%',
                'OR:description:LIKE' => '%' . $search . '%'
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
     * Filter the query by the valueField of MODx.combo.Context to get the initially value displayed right
     *
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $key = $this->getProperty('key', '');
        if (!empty($key)) {
            $c->where([
                $c->getAlias() . '.key:IN' => is_string($key) ? explode(',', $key) : $key,
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
        $permissions = [
            'create' => $this->canCreate && $object->checkPolicy('save'),
            'duplicate' => $this->canCreate && $object->checkPolicy('copy'),
            'update' => $this->canUpdate && $object->checkPolicy('save'),
            'delete' => $this->canDelete && $object->checkPolicy('remove')
        ];

        // $policies = [
        //     'save' => $object->checkPolicy('save'),
        //     'copy' => $object->checkPolicy('copy'),
        //     'remove' => $object->checkPolicy('remove')
        // ];
        //
        // $msg = "\r\n context:, \$policies:\r\n" . print_r($policies, true);
        // $this->modx->log(\modX::LOG_LEVEL_ERROR, $msg, '', __CLASS__);

        $contextData = $object->toArray();

        $contextKey = $object->get('key');
        $coreContexts = ['mgr', 'web'];
        $isCoreContext = in_array($contextKey, $coreContexts);

        if ($isCoreContext) {
            $baseKey = '_context_' . strtolower(str_replace(' ', '', $contextKey)) . '_';
            $contextData['name_trans'] = $this->modx->lexicon($baseKey . 'name');
            $contextData['description_trans'] = $this->modx->lexicon($baseKey . 'description');
        }

        $contextData['reserved'] = ['key' => $coreContexts, 'name' => ['Manager']];
        $contextData['isProtected'] = $isCoreContext ? true : false ;
        $contextData['creator'] = $isCoreContext ? 'modx' : strtolower($this->modx->lexicon('user')) ;
        if ($isCoreContext) {
            unset($permissions['delete']);
        }
        $contextData['permissions'] = $permissions;

        return $contextData;
    }
}
