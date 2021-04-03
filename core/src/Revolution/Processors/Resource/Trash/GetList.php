<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Resource\Trash;

use MODX\Revolution\modContext;
use MODX\Revolution\Processors\Model\GetListProcessor;
use MODX\Revolution\modResource;
use MODX\Revolution\modUser;
use PDO;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Gets a list of resources for trash manager.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @return array An array of modResources
 */
class GetList extends GetListProcessor
{
    public $classKey = modResource::class;

    public $languageTopics = ['resource'];

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

        $c->select([
            $this->modx->getSelectColumns(modResource::class, 'modResource'),
            'User.username as deletedby_name',
            'Context.name as context_name'
        ]);

        $c->leftJoin(modUser::class, 'User', 'modResource.deletedby = User.id');
        $c->leftJoin(modContext::class, 'Context', 'modResource.context_key = Context.key');

        // TODO add only resources if we have the save permission here (on the context!!)
        // we need the following permissions:
        // undelete_document - to restore the document
        // delete_document - thats perhaps not necessary, because all documents are already deleted
        // but we need the purge_deleted permission - for every single file

        if (!empty($query)) {
            $c->where(['modResource.pagetitle:LIKE' => '%' . $query . '%']);
            $c->orCondition(['modResource.longtitle:LIKE' => '%' . $query . '%']);
        }
        if (!empty($context)) {
            $c->where(['modResource.context_key' => $context]);
        }
        if ($deleted = $this->getDeleted()) {
            $c->where(['modResource.id:IN' => $deleted]);
        } else {
            $c->where(['modResource.id:IN' => 0]);
        }

        return $c;
    }

    public function getDeleted()
    {
        $c = $this->modx->newQuery($this->classKey);
        $c->select($this->modx->getSelectColumns($this->classKey, $c->getAlias(), '', ['id', 'context_key']));
        $c->where([
            $c->getAlias() . '.deleted' => true
        ]);
        if ($c->prepare() && $c->stmt->execute()) {
            $resources = $c->stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $deleted = [];
        foreach ($resources as $resource) {
            $deleted[] = (int)$resource['id'];
            $children = $this->modx->getChildIds($resource['id'], 10, ['context' => $resource['context_key']]);
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
        if (!$context) return [];

        $charset = $this->modx->getOption('modx_charset', null, 'UTF-8');
        $objectArray = $object->toArray();
        $objectArray['pagetitle'] = htmlentities($objectArray['pagetitle'], ENT_COMPAT, $charset);
        $objectArray['content'] = htmlentities($objectArray['content'], ENT_COMPAT, $charset);

        // to enable a better detection of the resource's location, we also construct the
        // parent-child path to the resource

        $parents = [];
        $parent = $objectArray['parent'];

        while ($parent != 0) {
            $parentObject = $this->modx->getObject(modResource::class, $parent);
            if ($parentObject) {
                $parents[] = $parentObject;
                $parent = $parentObject->get('parent');
            }
            else {
                break;
            }
        }

        $parentPath = "";
        foreach ($parents as $parent) {
            $parentPath = $parent->get('pagetitle') . " (" . $parent->get('id') . ") > " . $parentPath;
        }
        $objectArray['parentPath'] = "[" . $objectArray['context_key'] . "] " . $parentPath;

        //  TODO implement permission checks for every resource and return only resources user is allowed to see

        // show the permissions for the context
        $canView = $this->modx->hasPermission('view_document');
        $canPurge = $this->modx->hasPermission('purge_deleted');
        $canUndelete = $this->modx->hasPermission('undelete_document');
        $canPublish = $this->modx->hasPermission('publish_document');
        $canSave = $this->modx->hasPermission('save_document');
        $canEdit = $this->modx->hasPermission('edit_document');
        $canList = $this->modx->hasPermission('list');
        $canLoad = $this->modx->hasPermission('load');

        $objectArray['iconCls'] = $this->modx->getOption('mgr_source_icon', null, 'icon-folder-open-o');

        $cls = [];
        $cls[] = 'restore';
        $cls[] = 'purge';
        $cls[] = 'undelete_document';

        $cls = [];
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
