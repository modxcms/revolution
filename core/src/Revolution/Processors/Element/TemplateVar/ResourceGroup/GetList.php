<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\TemplateVar\ResourceGroup;


use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modResourceGroup;
use MODX\Revolution\modTemplateVarResourceGroup;

/**
 * Gets a list of resource groups associated to a TV.
 *
 * @property integer $tv    The ID of the TV
 *
 * @property integer $start (optional) The record to start at. Defaults to 0.
 * @property integer $limit (optional) The number of records to limit to. Defaults
 * to 20.
 * @property string  $sort  (optional) The column to sort by. Defaults to name.
 * @property string  $dir   (optional) The direction of the sort. Defaults to ASC.
 *
 * @package MODX\Revolution\Processors\Element\TemplateVar\ResourceGroup
 */
class GetList extends Processor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('view_tv');
    }

    public function getLanguageTopics()
    {
        return ['tv'];
    }

    public function initialize()
    {
        $this->setDefaultProperties([
            'start' => 0,
            'limit' => 20,
            'sort' => 'name',
            'dir' => 'ASC',
            'tv' => false,
        ]);

        return true;
    }

    public function process()
    {
        $data = $this->getData();

        $list = [];
        /** @var modResourceGroup $resourceGroup */
        foreach ($data['results'] as $resourceGroup) {
            $resourceGroupArray = $this->prepareRow($resourceGroup);
            if (!empty($resourceGroupArray)) {
                $list[] = $resourceGroupArray;
            }
        }

        return $this->outputArray($list, $data['total']);
    }

    /**
     * Get the Resource Group objects
     *
     * @return array
     */
    public function getData()
    {
        $data = [];
        $limit = $this->getProperty('limit');
        $isLimit = !empty($limit);

        $c = $this->modx->newQuery(modResourceGroup::class);
        $data['total'] = $this->modx->getCount(modResourceGroup::class, $c);

        $c->sortby($this->getProperty('sort'), $this->getProperty('dir'));
        if ($isLimit) {
            $c->limit($limit, $this->getProperty('start'));
        }
        $data['results'] = $this->modx->getCollection(modResourceGroup::class, $c);

        return $data;
    }

    /**
     * Prepare object for iteration
     *
     * @param modResourceGroup $resourceGroup
     *
     * @return array
     */
    public function prepareRow(modResourceGroup $resourceGroup)
    {
        if ($this->getProperty('tv')) {
            $rgtv = $this->modx->getObject(modTemplateVarResourceGroup::class, [
                'tmplvarid' => $this->getProperty('tv'),
                'documentgroup' => $resourceGroup->get('id'),
            ]);
        } else {
            $rgtv = null;
        }

        $resourceGroupArray = $resourceGroup->toArray();
        $resourceGroupArray['access'] = $rgtv ? true : false;
        $resourceGroupArray['menu'] = [];

        return $resourceGroupArray;
    }
}
