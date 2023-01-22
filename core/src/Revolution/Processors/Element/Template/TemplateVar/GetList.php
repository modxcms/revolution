<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\Template\TemplateVar;


use MODX\Revolution\Processors\Model\GetListProcessor;
use MODX\Revolution\modTemplate;
use xPDO\Om\xPDOObject;

/**
 * Gets a list of TVs, marking ones associated with the template.
 *
 * @property integer $template (optional) The template which the TVs are associated
 * to.
 * @property integer $start    (optional) The record to start at. Defaults to 0.
 * @property integer $limit    (optional) The number of records to limit to. Defaults
 * to 20.
 * @property string  $sort     (optional) The column to sort by. Defaults to name.
 * @property string  $dir      (optional) The direction of the sort. Defaults to ASC.
 *
 * @package MODX\Revolution\Processors\Element\Template\TemplateVar
 */
class GetList extends GetListProcessor
{
    public $classKey = modTemplate::class;
    public $primaryKeyField = 'template';
    public $objectType = 'template';
    public $permission = ['view_tv' => true, 'view_template' => true];
    public $languageTopics = ['template'];

    /**
     * Prepare conditions for TV list
     *
     * @return array
     */
    public function prepareConditions()
    {
        $conditions = [];

        $category = (integer)$this->getProperty('category', 0);
        if ($category) {
            $conditions[] = ['category' => $category];
        }

        $query = $this->getProperty('query', '');
        if (!empty($query)) {
            $conditions[] = [
                'name:LIKE' => '%' . $query . '%',
                'OR:caption:LIKE' => '%' . $query . '%',
                'OR:description:LIKE' => '%' . $query . '%',
            ];
        }

        return $conditions;
    }

    /**
     * Load template which TVs are assigned to or new template
     *
     * @return modTemplate
     */
    public function loadTemplate()
    {
        $templateId = $this->getProperty($this->primaryKeyField, 0);
        /** @var modTemplate $template */
        $template = ($templateId > 0) ?
            $this->modx->getObject($this->classKey, $templateId) :
            $this->modx->newObject($this->classKey);

        return $template;
    }

    /**
     * {@inheritdoc}
     * @return array
     */
    public function getData()
    {
        $sort = $this->getProperty('sort');
        $dir = $this->getProperty('dir');
        $limit = intval($this->getProperty('limit'));
        $start = intval($this->getProperty('start'));
        $conditions = $this->prepareConditions();

        $template = $this->loadTemplate();
        $tvList = $template->getTemplateVarList([$sort => $dir], $limit, $start, $conditions);
        $data = [
            'total' => $tvList['total'],
            'results' => $tvList['collection'],
        ];

        return $data;
    }

    /**
     * {@inheritdoc}
     * @param xPDOObject $object
     *
     * @return array|mixed
     */
    public function prepareRow(xPDOObject $object)
    {
        $tvArray = $object->get(['id', 'name', 'caption', 'tv_rank', 'category_name']);
        $tvArray['access'] = (boolean)$object->get('access');

        $tvArray['perm'] = [];
        if ($this->modx->hasPermission('edit_tv')) {
            $tvArray['perm'][] = 'pedit';
        }

        return $tvArray;
    }
}
