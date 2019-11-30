<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Menu;

use MODX\Revolution\modMenu;
use MODX\Revolution\Processors\Model\GetListProcessor;
use xPDO\Om\xPDOObject;

/**
 * Get a list of menu items
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @param string $sort (optional) The column to sort by. Defaults to menuindex.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 * @package MODX\Revolution\Processors\System\Menu
 */
class GetList extends GetListProcessor
{
    public $classKey = modMenu::class;
    public $objectType = 'menu';
    public $primaryKeyField = 'text';
    public $languageTopics = ['action', 'menu', 'topmenu'];
    public $permission = 'menus';
    public $defaultSortField = 'menuindex';

    /**
     * @return bool
     */
    public function initialize()
    {
        $initialized = parent::initialize();
        $this->setDefaultProperties([
            'showNone' => false,
        ]);

        return $initialized;
    }

    /**
     * @param array $list
     * @return array
     */
    public function beforeIteration(array $list)
    {
        if ($this->getProperty('showNone', false)) {
            $list = [
                '0' => [
                    'text' => '',
                    'text_lex' => "({$this->modx->lexicon('none')})",
                ],
            ];
        }

        return $list;
    }

    /**
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $objectArray = $object->toArray();
        $namespace = $object->get('namespace');
        if (!in_array($namespace, ['core', '', null], true)) {
            $this->modx->lexicon->load($namespace . ':default');
        }
        $objectArray['text_lex'] = $this->modx->lexicon($objectArray['text']);

        return $objectArray;
    }
}
