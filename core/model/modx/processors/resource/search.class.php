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
 * Searches for specific resources and returns them in an array.
 *
 * @param integer $start The page to start on
 * @param integer $limit (optional) The number of results to limit by
 * @param string $sort The column to sort by
 * @param string $dir The direction to sort
 * @return array An array of modResources
 *
 * @package modx
 * @subpackage processors.resource
 */
class modResourceSearchProcessor extends modObjectGetListProcessor {
    public $classKey = 'modResource';
    public $languageTopics = array('resource');
    public $permission = 'search';
    public $defaultSortField = 'pagetitle';

    /** @var array $contextKeys */
    public $contextKeys = array();
    /** @var array $actions */
    public $actions = array();
    /** @var string $charset */
    public $charset = 'UTF-8';
    /** @var boolean $canEdit */
    public $canEdit = false;

    public function beforeQuery() {
        $this->contextKeys = $this->getContextKeys();
        if (empty($this->contextKeys)) {
            return $this->modx->lexicon('permission_denied');
        }
        return true;
    }

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $where = array('context_key:IN' => $this->contextKeys);
        $conditions = $this->getProperties();
        if (!empty($conditions['id'])) $where['id'] = $conditions['id'];
        if (!empty($conditions['parent'])) $where['parent'] = $conditions['parent'];
        if (!empty($conditions['template'])) $where['template'] = $conditions['template'];
        if (!empty($conditions['pagetitle'])) $where['pagetitle:LIKE'] = '%'.$conditions['pagetitle'].'%';
        if (!empty($conditions['longtitle'])) $where['longtitle:LIKE'] = '%'.$conditions['longtitle'].'%';
        if (!empty($conditions['introtext'])) $where['introtext:LIKE'] = '%'.$conditions['introtext'].'%';
        if (!empty($conditions['description'])) $where['description:LIKE'] = '%'.$conditions['description'].'%';
        if (!empty($conditions['alias'])) $where['alias:LIKE'] = '%'.$conditions['alias'].'%';
        if (!empty($conditions['menutitle'])) $where['menutitle:LIKE'] = '%'.$conditions['menutitle'].'%';
        if (!empty($conditions['content'])) $where['content:LIKE'] = '%'.$conditions['content'].'%';

        if (!empty($conditions['published'])) $where['published'] = true;
        if (!empty($conditions['unpublished'])) $where['published'] = false;
        if (!empty($conditions['deleted'])) $where['deleted'] = true;
        if (!empty($conditions['undeleted'])) $where['deleted'] = false;

        $c->where($where);
        return $c;
    }

    /**
     * Get a collection of Context keys that the User can access for all the Resources
     * @return array
     */
    public function getContextKeys() {
        $contextKeys = array();
        $contexts = $this->modx->getCollection('modContext', array('key:!=' => 'mgr'));
        /** @var modContext $context */
        foreach ($contexts as $context) {
            if ($context->checkPolicy('list')) {
                $contextKeys[] = $context->get('key');
            }
        }
        return $contextKeys;
    }

    public function beforeIteration(array $list) {
        $this->charset = $this->modx->getOption('modx_charset',null,'UTF-8');
        $this->canEdit = $this->modx->hasPermission('edit_document');
        return $list;
    }

    public function prepareRow(xPDOObject $object) {
        $objectArray = $object->toArray();
        $objectArray['menu'] = array();
        $objectArray['pagetitle'] = htmlentities($objectArray['pagetitle'],ENT_COMPAT,$this->charset);
        $objectArray['description'] = htmlentities($objectArray['description'],ENT_COMPAT,$this->charset);
        if ($this->canEdit) {
            $objectArray['menu'][] = array(
                'text' => $this->modx->lexicon('resource_edit'),
                'params' => array('a' => 'resource/update'),
            );
        }
        return $objectArray;
    }
}
return 'modResourceSearchProcessor';
