<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\ResourceGroup;

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modResource;
use MODX\Revolution\modResourceGroup;
use ReflectionClass;

/**
 * Get the resource groups as nodes
 * @param string $id The ID of the parent node
 * @package MODX\Revolution\Processors\Security\ResourceGroup
 */
class GetNodes extends Processor
{
    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('resourcegroup_view');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['access'];
    }

    /**
     * @return bool
     */
    public function initialize()
    {
        $this->setDefaultProperties([
            'start' => 0,
            'limit' => 10,
            'sort' => 'name',
            'dir' => 'ASC',
            'id' => '',
        ]);

        return true;
    }

    /**
     * @return mixed|string
     */
    public function process()
    {
        /* get parent */
        $id = $this->getProperty('id');
        $id = empty($id) ? 0 : str_replace('n_dg_', '', $id);

        $list = [];
        if (empty($id)) {
            $resourceGroups = $this->getResourceGroups();
            /** @var modResourceGroup $resourceGroup */
            foreach ($resourceGroups as $resourceGroup) {
                $list[] = [
                    'text' => $resourceGroup->get('name') . ' (' . $resourceGroup->get('id') . ')',
                    'id' => 'n_dg_' . $resourceGroup->get('id'),
                    'leaf' => false,
                    'type' => modResourceGroup::class,
                    'cls' => 'icon-resourcegroup',
                    'iconCls' => 'icon-files-o',
                    'data' => $resourceGroup->toArray(),
                ];
            }
        } else {
            if ($this->modx->hasPermission('resourcegroup_resource_list')) {
                /** @var modResourceGroup $resourceGroup */
                $resourceGroup = $this->modx->getObject(modResourceGroup::class, $id);
                if ($resourceGroup) {
                    $resources = $resourceGroup->getResources();
                    /** @var modResource $resource */
                    foreach ($resources as $resource) {
                        $class = [];
                        $hasChildren = $resource->get('childrenCount') > 0;
                        if (!$resource->isfolder) {
                            $class[] = 'x-tree-node-leaf';
                        }
                        if ($hasChildren) {
                            $class[] = 'is_folder';
                        }
                        if (!$resource->get('published')) {
                            $class[] = 'unpublished';
                        }
                        if ($resource->get('deleted')) {
                            $class[] = 'deleted';
                        }
                        if ($resource->get('hidemenu')) {
                            $class[] = 'hidemenu';
                        }

                        if (!empty($this->permissions['save_document'])) {
                            $class[] = $this->permissions['save_document'];
                        }
                        if (!empty($this->permissions['view_document'])) {
                            $class[] = $this->permissions['view_document'];
                        }
                        if (!empty($this->permissions['edit_document'])) {
                            $class[] = $this->permissions['edit_document'];
                        }
                        if (!empty($this->permissions['resource_duplicate'])) {
                            if ($resource->parent != $this->defaultRootId || $this->modx->hasPermission('new_document_in_root')) {
                                $class[] = $this->permissions['resource_duplicate'];
                            }
                        }
                        if ($resource->allowChildrenResources && !$resource->deleted) {
                            if (!empty($this->permissions['new_document'])) {
                                $class[] = $this->permissions['new_document'];
                            }
                            if (!empty($this->permissions['new_symlink'])) {
                                $class[] = $this->permissions['new_symlink'];
                            }
                            if (!empty($this->permissions['new_weblink'])) {
                                $class[] = $this->permissions['new_weblink'];
                            }
                            if (!empty($this->permissions['new_static_resource'])) {
                                $class[] = $this->permissions['new_static_resource'];
                            }
                            if (!empty($this->permissions['resource_quick_create'])) {
                                $class[] = $this->permissions['resource_quick_create'];
                            }
                        }
                        if (!empty($this->permissions['resource_quick_update'])) {
                            $class[] = $this->permissions['resource_quick_update'];
                        }
                        if (!empty($this->permissions['delete_document'])) {
                            $class[] = $this->permissions['delete_document'];
                        }
                        if (!empty($this->permissions['undelete_document'])) {
                            $class[] = $this->permissions['undelete_document'];
                        }
                        if (!empty($this->permissions['publish_document'])) {
                            $class[] = $this->permissions['publish_document'];
                        }
                        if (!empty($this->permissions['unpublish_document'])) {
                            $class[] = $this->permissions['unpublish_document'];
                        }

                        // Assign an icon class based on the class_key
                        try {
                            $reflectionClass = new ReflectionClass($resource->get('class_key'));
                            $classKey = strtolower($reflectionClass->getShortName());
                        } catch (ReflectionException $e) {
                            $classKey = strtolower($resource->get('class_key'));
                        }
                        if (substr($classKey, 0, 3) === 'mod') {
                            $classKey = substr($classKey, 3);
                        }

                        $iconCls = [];

                        $contentType = $resource->getOne('ContentType');
                        if ($contentType && $contentType->get('icon')) {
                            $iconCls = [$contentType->get('icon')];
                        }

                        $template = $resource->getOne('Template');
                        $tplIcon = '';
                        if ($template && $template->get('icon')) {
                            $tplIcon = $template->get('icon');
                            $iconCls = [$template->get('icon')];
                        }

                        $classKeyIcon = $this->modx->getOption('mgr_tree_icon_' . $classKey, null, 'tree-resource', true);
                        if (empty($iconCls)) {
                            $iconCls[] = $classKeyIcon;
                        }

                        switch ($classKey) {
                            case 'weblink':
                                $iconCls[] = $this->modx->getOption('mgr_tree_icon_weblink', null, 'tree-weblink');
                                break;

                            case 'symlink':
                                $iconCls[] = $this->modx->getOption('mgr_tree_icon_symlink', null, 'tree-symlink');
                                break;

                            case 'staticresource':
                                $iconCls[] = $this->modx->getOption('mgr_tree_icon_staticresource', null, 'tree-static-resource');
                                break;
                        }

                        // Icons specific with the context and resource ID for super specific tweaks
                        $iconCls[] = 'icon-' . $resource->get('context_key') . '-' . $resource->get('id');
                        $iconCls[] = 'icon-parent-' . $resource->get('context_key') . '-' . $resource->get('parent');

                        // Modifiers to indicate resource _state_
                        if ($hasChildren || $resource->isfolder) {
                            if (empty($tplIcon) && $classKeyIcon === 'tree-resource') {
                                $iconCls[] = $this->modx->getOption('mgr_tree_icon_folder', null, 'parent-resource');
                            }
                        }

                        // Add icon class - and additional description to the tooltip - if the resource is locked.
                        $locked = $resource->getLock();
                        if ($locked && $locked != $this->modx->user->get('id')) {
                            $iconCls[] = 'locked-resource';
                        }

                        $list[] = [
                            'text' => $resource->get('pagetitle') . ' (' . $resource->get('id') . ')',
                            'id' => 'n_' . $resource->get('id') . '_' . $resourceGroup->get('id'),
                            'leaf' => true,
                            'type' => modResource::class,
                            'cls' => implode(' ', $class),
                            'iconCls' => implode(' ', $iconCls),
                        ];
                    }
                }
            }
        }

        return $this->toJSON($list);
    }

    /**
     * Get the Resource Groups at this level
     * @return array
     */
    public function getResourceGroups()
    {
        $c = $this->modx->newQuery(modResourceGroup::class);

        $c->sortby($this->getProperty('sort'), $this->getProperty('dir'));

        if ($this->getProperty('limit') > 0) {
            $c->limit($this->getProperty('limit'), $this->getProperty('start'));
        }

        return $this->modx->getCollection(modResourceGroup::class, $c);
    }
}
