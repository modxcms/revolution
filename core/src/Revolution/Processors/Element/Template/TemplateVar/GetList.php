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
use MODX\Revolution\modTemplateVar;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

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

    protected $category = 0;
    protected $query = '';
    protected $isFiltered = false;

    /**
     * {@inheritDoc}
     */
    public function initialize()
    {
        $this->category = (int)$this->getProperty('category', 0);
        $this->query = $this->getProperty('query', '');
        $this->isFiltered = $this->category > 0 || $this->query;
        return parent::initialize();
    }

    /**
     * Prepare conditions for TV list
     */
    public function prepareConditions(): array
    {
        $conditions = [];

        if (!$this->isFiltered) {
            return $conditions;
        }

        if ($this->category) {
            $conditions[] = ['category' => $this->category];
        }

        if (!empty($this->query)) {
            $conditions[] = [
                'name:LIKE' => '%' . $this->query . '%',
                'OR:caption:LIKE' => '%' . $this->query . '%',
                'OR:description:LIKE' => '%' . $this->query . '%'
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
     * {@inheritDoc}
     */
    public function getData(): array
    {
        $sort = $this->getProperty('sort');
        $dir = $this->getProperty('dir');
        $limit = (int)$this->getProperty('limit');
        $start = (int)$this->getProperty('start');
        $conditions = $this->prepareConditions();

        $template = $this->loadTemplate();
        $tvList = $template->getTemplateVarList([$sort => $dir], $limit, $start, $conditions);

        $data = [
            'total' => $this->isFiltered ? $this->getFilteredCount() : $tvList['total'],
            'results' => $tvList['collection'],
        ];

        return $data;
    }

    /**
     * Workaround to get correct total count when list is filtered
     */
    public function getFilteredCount(): int
    {
        $c = $this->modx->newQuery(modTemplateVar::class);
        $c = $this->prepareQueryBeforeCount($c);
        $filteredCount = $this->modx->getCount(modTemplateVar::class, $c);

        return $filteredCount;
    }

    /**
     * {@inheritDoc}
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        if (!$this->isFiltered) {
            return $c;
        }

        if ($this->category) {
            $c->where(
                ['category' => $this->category]
            );
        }

        if ($this->query) {
            $c->where([
                'name:LIKE' => '%' . $this->query . '%',
                'OR:caption:LIKE' => '%' . $this->query . '%',
                'OR:description:LIKE' => '%' . $this->query . '%',
            ]);
        }
        return $c;
    }

    /**
     * {@inheritDoc}
     */
    public function prepareRow(xPDOObject $object)
    {
        $tvArray = $object->get(['id', 'name', 'caption', 'tv_rank', 'category_name']);
        $tvArray['access'] = (bool)$object->get('access');

        $tvArray['perm'] = [];
        if ($this->modx->hasPermission('edit_tv')) {
            $tvArray['perm'][] = 'pedit';
        }

        return $tvArray;
    }
}
