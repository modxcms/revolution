<?php

namespace MODX\Processors\Security\ResourceGroup;

use MODX\modResource;
use MODX\modResourceGroup;
use MODX\Processors\modProcessor;

/**
 * Get the resource groups as nodes
 *
 * @param string $id The ID of the parent node
 *
 * @package modx
 * @subpackage processors.security.resourcegroup
 */
class GetNodes extends modProcessor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('resourcegroup_view');
    }


    public function getLanguageTopics()
    {
        return ['access'];
    }


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
                    'type' => 'modResourceGroup',
                    'cls' => 'icon-resourcegroup',
                    'iconCls' => 'icon-files-o',
                    'data' => $resourceGroup->toArray(),
                ];
            }
        } else {
            if ($this->modx->hasPermission('resourcegroup_resource_list')) {
                /** @var modResourceGroup $resourceGroup */
                $resourceGroup = $this->modx->getObject('modResourceGroup', $id);
                if ($resourceGroup) {
                    $resources = $resourceGroup->getResources();
                    /** @var modResource $resource */
                    foreach ($resources as $resource) {
                        $list[] = [
                            'text' => $resource->get('pagetitle') . ' (' . $resource->get('id') . ')',
                            'id' => 'n_' . $resource->get('id') . '_' . $resourceGroup->get('id'),
                            'leaf' => true,
                            'type' => 'modResource',
                            'cls' => 'icon-' . $resource->get('class_key'),
                            'iconCls' => 'icon-file',
                        ];
                    }
                }
            }
        }

        return $this->toJSON($list);
    }


    /**
     * Get the Resource Groups at this level
     *
     * @return array
     */
    public function getResourceGroups()
    {
        $c = $this->modx->newQuery('modResourceGroup');

        $c->sortby($this->getProperty('sort'), $this->getProperty('dir'));
        if ($this->getProperty('limit') > 0) {
            $c->limit($this->getProperty('limit'), $this->getProperty('start'));
        }

        return $this->modx->getCollection('modResourceGroup', $c);
    }
}
