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

use MODX\Revolution\Formatter\modManagerDateFormatter;
use MODX\Revolution\modContext;
use MODX\Revolution\modResource;
use MODX\Revolution\modUser;
use MODX\Revolution\Processors\Model\GetListProcessor;
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

    public $canPurge = false;
    public $canUndelete = false;
    public $canUPublish = false;

    private modManagerDateFormatter $formatter;

    public function initialize()
    {
        $this->formatter = $this->modx->services->get(modManagerDateFormatter::class);

        $canChange = $this->modx->hasPermission('save_document') && $this->modx->hasPermission('edit_document');
        $this->canPurge = $canChange && $this->modx->hasPermission('purge_deleted');
        $this->canUndelete = $canChange && $this->modx->hasPermission('undelete_document');
        $this->canUPublish = $canChange && $this->modx->hasPermission('publish_document');

        return parent::initialize();
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        if ($deleted = $this->getDeleted()) {
            $c->where(['modResource.id:IN' => $deleted]);
        } else {
            $c->where(['modResource.id' => 0]);
            return $c;
        }

        $query = $this->getProperty('query');
        $context = $this->getProperty('context');

        $c->select([
            $this->modx->getSelectColumns(modResource::class, 'modResource'),
            'User.username as deletedby_name',
            'Context.name as context_name'
        ]);

        $c->leftJoin(modUser::class, 'User', 'modResource.deletedby = User.id');
        $c->leftJoin(modContext::class, 'Context', 'modResource.context_key = Context.key');

        /*
            TODO:
            Add only resources if we have the save permission here (on the context!!)
            we need the following permissions:
                undelete_document - to restore the document
                delete_document - that's perhaps not necessary, because all documents are already deleted
                but we need the purge_deleted permission - for every single file
        */
        if ($deleted = $this->getDeleted()) {
            $c->where(['modResource.id:IN' => $deleted]);
        } else {
            $c->where(['modResource.id:IN' => 0]);
        }
        if (!empty($query)) {
            $c->where([
                'modResource.pagetitle:LIKE' => '%' . $query . '%',
                'OR:modResource.longtitle:LIKE' => '%' . $query . '%'
            ]);
        }
        if (!empty($context)) {
            $c->where(['modResource.context_key' => $context]);
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
        /*
            TODO:
            Filter out resources where user does not have at least one of the permissions
            applicable to the actions available in the trash manager:
            1. undelete_document - restore resource
            2. purge_deleted - permanently destroy resource
        */
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
        if (!$context) {
            return [];
        }

        $permissions = [
            'purge' => $this->canPurge && $object->checkPolicy('purge_deleted'),
            'undelete' => $this->canUndelete && $object->checkPolicy('undelete_document'),
            'publish' => $this->canUPublish && $object->checkPolicy('publish_document')
        ];

        $charset = $this->modx->getOption('modx_charset', null, 'UTF-8');
        $resourceData = $object->toArray();
        $resourceData['pagetitle'] = htmlentities($resourceData['pagetitle'], ENT_COMPAT, $charset);
        $resourceData['content'] = htmlentities($resourceData['content'], ENT_COMPAT, $charset);

        // to enable a better detection of the resource's location, we also construct the
        // parent-child path to the resource

        $parents = [];
        $parent = $resourceData['parent'];

        while ($parent != 0) {
            $parentObject = $this->modx->getObject(modResource::class, $parent);
            if ($parentObject) {
                $parents[] = $parentObject;
                $parent = $parentObject->get('parent');
            } else {
                break;
            }
        }

        $parentPath = '';
        foreach ($parents as $parent) {
            $parentPath = $parent->get('pagetitle') . ' (' . $parent->get('id') . ') > ' . $parentPath;
        }
        $resourceData['parentPath'] = '[' . $resourceData['context_key'] . '] ' . $parentPath;

        $resourceData['deletedon'] = $this->formatter->formatDateTime($resourceData['deletedon']);
        $resourceData['permissions'] = $permissions;

        return $resourceData;
    }
}
