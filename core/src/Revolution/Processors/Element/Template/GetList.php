<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\Template;


use MODX\Revolution\modTemplate;
use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOQuery;

/**
 * Grabs a list of templates.
 *
 * @property integer $start (optional) The record to start at. Defaults to 0.
 * @property integer $limit (optional) The number of records to limit to. Defaults
 * to 20.
 * @property string  $sort  (optional) The column to sort by. Defaults to name.
 * @property string  $dir   (optional) The direction of the sort. Defaults to ASC.
 *
 * @package MODX\Revolution\Processors\Element\Template
 */
class GetList extends \MODX\Revolution\Processors\Element\GetList
{
    public $classKey = modTemplate::class;
    public $languageTopics = ['template', 'category'];
    public $defaultSortField = 'templatename';
    public $permission = 'view_template';

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c = parent::prepareQueryBeforeCount($c);
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where([
                'templatename:LIKE' => "$query%",
            ]);
        }

        return $c;
    }

    public function beforeIteration(array $list)
    {
        if ($this->getProperty('combo', false) && !$this->getProperty('query', false)) {
            $empty = [
                'id' => 0,
                'templatename' => $this->modx->lexicon('template_empty'),
                'description' => '',
                'editor_type' => 0,
                'icon' => '',
                'template_type' => 0,
                'content' => '',
                'locked' => false,
            ];
            $empty['category_name'] = '';
            $list[] = $empty;
        }

        return $list;
    }

    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->toArray();
        $objectArray['category_name'] = $object->get('category_name');
        unset($objectArray['content']);

        return $objectArray;
    }
}
