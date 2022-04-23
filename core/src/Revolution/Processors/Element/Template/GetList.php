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
 * @return array An array of modTemplate
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
                'templatename:LIKE' => '%' . $query . '%'
            ]);
        }

        return $c;
    }

    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c = parent::prepareQueryAfterCount($c);
        $c->sortby('category_name');
        $c->sortby('templatename');

        return $c;
    }

    public function beforeIteration(array $list)
    {
        if ($this->getProperty('combo', false) && !$this->getProperty('query', false)) {
            $list[] = [
                'id'            => 0,
                'templatename'  => $this->modx->lexicon('template_empty'),
                'description'   => '',
                'category_name' => '',
                'time'          => time()
            ];
        }

        return $list;
    }

    public function prepareRow(xPDOObject $object)
    {
        $preview = $object->getPreviewUrl();

        if (!empty($preview)) {
            $imageQuery = http_build_query([
                'src'           => $preview,
                'w'             => 335,
                'h'             => 236,
                'HTTP_MODAUTH'  => $this->modx->user->getUserToken($this->modx->context->get('key')),
                'zc'            => 1
            ]);

            $preview = $this->modx->getOption('connectors_url', MODX_CONNECTORS_URL) . 'system/phpthumb.php?' . urldecode($imageQuery);
        }

        return [
            'id'            => $object->get('id'),
            'templatename'  => $object->get('templatename'),
            'description'   => $object->get('description'),
            'category_name' => $object->get('category_name'),
            'preview'       => $preview,
            'time'          => time()
        ];
    }
}
