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
 * Gets a list of resources for trash manager.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string  $sort  (optional) The column to sort by. Defaults to name.
 * @param string  $dir   (optional) The direction of the sort. Defaults to ASC.
 *
 * @return array An array of modResources
 */
class modResourceTrashGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'modResource';

    public $languageTopics = array('resource');

    public $defaultSortField = 'pagetitle';

    public $permission = 'view';

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = $this->getProperty('query');
        $context = $this->getProperty('context');

        $c->select(array(
            $this->modx->getSelectColumns('modResource', 'modResource'),
            'User.username as deletedby_name',
            'Context.name as context_name'
        ));

        $c->leftJoin('modUser', 'User', 'modResource.deletedby = User.id');
        $c->leftJoin('modContext', 'Context', 'modResource.context_key = Context.key');

        // TODO add only resources if we have the save permission here (on the context!!)
        // we need the following permissions:
        // undelete_document - to restore the document
        // delete_document - thats perhaps not necessary, because all documents are already deleted
        // but we need the purge_deleted permission - for every single file

        if (!empty($query)) {
            $c->where(array('modResource.pagetitle:LIKE' => '%' . $query . '%'));
            $c->orCondition(array('modResource.longtitle:LIKE' => '%' . $query . '%'));
        }
        if (!empty($context)) {
            $c->where(array('modResource.context_key' => $context));
        }
        if ($deleted = $this->getDeleted()) {
            $c->where(array('modResource.id:IN' => $deleted));
        } else {
            $c->where(array('modResource.id' => 0));
        }

        return $c;
    }

    public function getDeleted()
    {
        $c = $this->modx->newQuery($this->classKey);
        $c->select($this->modx->getSelectColumns($this->classKey, $this->classKey, '', array('id', 'context_key')));
        $c->where(array(
            $this->classKey . '.deleted' => true
        ));
        if ($c->prepare() && $c->stmt->execute()) {
            $resources = $c->stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $deleted = array();
        foreach ($resources as $resource) {
            $deleted[] = (int)$resource['id'];
            $children = $this->modx->getChildIds($resource['id'], 10, array('context' => $resource['context_key']));
            $deleted = array_merge($deleted, $children);
        }
        return array_unique($deleted);
    }

    /**
     * @param modResource $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        // quick exit if we don't have access to the context
        // this is a strange workaround: obviously we can access the resources even if we don't have access to the context! Check that
        // TODO check if that is the same for resource groups
        $context = $this->modx->getContext($object->get('context_key'));
        if (!$context) return array();

        $charset = $this->modx->getOption('modx_charset', null, 'UTF-8');
        $objectArray = $object->toArray();
        $objectArray['pagetitle'] = htmlentities($objectArray['pagetitle'], ENT_COMPAT, $charset);
        $objectArray['content'] = htmlentities($objectArray['content'], ENT_COMPAT, $charset);

        // to enable a better detection of the resource's location, we also construct the
        // parent-child path to the resource

        $parents = array();
        $parent = $objectArray['parent'];

        while ($parent != 0) {
            $parentObj = $this->modx->getObject('modResource', $parent);
            if ($parentObj) {
                $parents[] = $parentObj;
                $parent = end($parents)->get('parent');
            } else {
                $parent = 0;
            }
        }

        $parentPath = "";
        foreach ($parents as $parent) {
            $parentPath = $parent->get('pagetitle') . " (".$parent->get('id') . ") > " . $parentPath;
        }
        $objectArray['parentPath'] =  "[" . $objectArray['context_key'] . "] " . $parentPath;

        //  TODO implement permission checks for every resource and return only resources user is allowed to see

        // show the permissions for the context
        $canView = $this->modx->hasPermission('view');
        $canPurge = $this->modx->hasPermission('purge_deleted');
        $canUndelete = $this->modx->hasPermission('undelete_document');
        $canPublish = $this->modx->hasPermission('publish');
        $canSave = $this->modx->hasPermission('save');
        $canEdit = $this->modx->hasPermission('edit');
        $canList = $this->modx->hasPermission('list');
        $canLoad = $this->modx->hasPermission('load');

        $objectArray['iconCls'] = $this->modx->getOption('mgr_source_icon', null, 'icon-folder-open-o');

        $cls = array();
        $cls[] = 'restore';
        $cls[] = 'purge';
        $cls[] = 'undelete_document';

        $cls = array();
        if ($object->checkPolicy('purge_deleted') && $canSave && $canEdit && $canPurge) {
            $cls[] = 'trashpurge';
        }
        if ($object->checkPolicy('undelete_document') && $canSave && $canEdit) {
            $cls[] = 'trashundelete';
        }
        if ($object->checkPolicy('save') && $canSave && $canEdit) {
            $cls[] = 'trashsave';
        }
        if ($object->checkPolicy('edit') && $canSave && $canEdit) {
            $cls[] = 'trashedit';
        }
        $cls[] = 'trashrow';

        $objectArray['cls'] = implode(' ', $cls);

        return $objectArray;
    }
}

return 'modResourceTrashGetListProcessor';

