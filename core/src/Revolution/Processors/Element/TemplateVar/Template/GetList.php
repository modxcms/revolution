<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\TemplateVar\Template;


use MODX\Revolution\modCategory;
use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modTemplate;
use MODX\Revolution\modTemplateVarTemplate;

/**
 * Grabs a list of templates associated with the TV
 *
 * @property integer $tv    The ID of the TV
 * @property integer $start (optional) The record to start at. Defaults to 0.
 * @property integer $limit (optional) The number of records to limit to. Defaults
 * to 20.
 * @property string  $sort  (optional) The column to sort by. Defaults to name.
 * @property string  $dir   (optional) The direction of the sort. Defaults to ASC.
 *
 * @package MODX\Revolution\Processors\Element\TemplateVar\Template
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
            'sort' => 'templatename',
            'dir' => 'ASC',
            'tv' => false,
        ]);

        return true;
    }

    public function process()
    {
        $data = $this->getData();

        $list = [];
        /** @var modTemplate $template */
        foreach ($data['results'] as $template) {
            $templateArray = $this->prepareRow($template);
            if (!empty($templateArray)) {
                $list[] = $templateArray;
            }
        }

        return $this->outputArray($list, $data['total']);
    }

    /**
     * Get the Template objects
     *
     * @return array
     */
    public function getData()
    {
        $data = [];
        $limit = $this->getProperty('limit');
        $isLimit = !empty($limit);

        /* query for templates */
        $c = $this->modx->newQuery(modTemplate::class);
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where([
                'templatename:LIKE' => "%$query%",
            ]);
        }
        $c->leftJoin(modCategory::class, 'Category');
        $category = $this->getProperty('category');
        if (!empty($category)) {
            $c->where([
                'Category.id' => $category,
            ]);
        }

        $data['total'] = $this->modx->getCount(modTemplate::class, $c);

        $c->leftJoin(modTemplateVarTemplate::class, 'TemplateVarTemplates', [
            'modTemplate.id = TemplateVarTemplates.templateid',
            'TemplateVarTemplates.tmplvarid' => $this->getProperty('tv'),
        ]);

        $c->select($this->modx->getSelectColumns(modTemplate::class, 'modTemplate'));
        $c->select([
            'category_name' => 'Category.category',
        ]);
        $c->select($this->modx->getSelectColumns(modTemplateVarTemplate::class, 'TemplateVarTemplates', '',
            ['tmplvarid']));
        $c->select(['access' => 'TemplateVarTemplates.tmplvarid']);
        $c->sortby($this->getProperty('sort'), $this->getProperty('dir'));
        if ($isLimit) {
            $c->limit($limit, $this->getProperty('start'));
        }
        $data['results'] = $this->modx->getCollection(modTemplate::class, $c);

        return $data;
    }

    /**
     * Prepare object for iteration
     *
     * @param modTemplate $template
     *
     * @return array
     */
    public function prepareRow(modTemplate $template)
    {
        $templateArray = $template->toArray();
        $templateArray['category_name'] = $template->get('category_name');
        unset($templateArray['content']);

        return $templateArray;
    }
}
