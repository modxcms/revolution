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
use xPDO\Om\xPDOQuery;

/**
 * Get the menu items, in node format
 * @param string $id The parent ID
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @package MODX\Revolution\Processors\System\Menu
 */
class GetNodes extends GetListProcessor
{
    public $classKey = modMenu::class;
    public $objectType = 'menu';
    public $primaryKeyField = 'text';
    public $languageTopics = ['action', 'menu', 'topmenu'];
    public $permission = 'menus';
    public $defaultSortField = 'menuindex';

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function initialize()
    {
        $this->setDefaultProperties([
            'id' => '',
        ]);
        $id = $this->getProperty('id');
        $this->setProperty('id', str_replace('n_', '', $id));

        return parent::initialize();
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->leftJoin($this->classKey, 'Children');
        $c->where([
            $c->getAlias() . '.parent' => $this->getProperty('id'),
        ]);
        $c->groupby($c->getAlias() . '.text');

        return parent::prepareQueryBeforeCount($c);
    }

    /**
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->select($this->modx->getSelectColumns(modMenu::class, 'modMenu'));
        $c->select([
            'COUNT(Children.text) AS childrenCount',
        ]);

        return $c;
    }

    /**
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $controller = $object->get('action');
        $namespace = $object->get('namespace');
        if (!in_array($namespace, ['core', '', null], true)) {
            $this->modx->lexicon->load($namespace . ':default');
        }
        $text = $this->modx->lexicon($object->get('text'));
        $desc = $this->modx->lexicon($object->get('description'));
        $text = htmlspecialchars($text, ENT_QUOTES, $this->modx->getOption('modx_charset', null, 'UTF-8'));

        $objectArray = [
            'text' => $text . ($controller !== '' ? ' <i>(' . $namespace . ':' . $controller . ')</i>' : ''),
            'id' => 'n_' . $object->get('text'),
            'cls' => 'icon-menu',
            'iconCls' => 'icon icon-' . ($object->get('childrenCount') > 0 ? ($object->get('parent') === '' ? 'navicon' : 'folder') : 'terminal'),
            'type' => 'menu',
            'pk' => $object->get('text'),
            // consider each node not being a "leaf" so we can drop records in it
            'leaf' => false,
            'data' => $object->toArray(),
            'qtip' => strip_tags($desc),
            'draggable' => !empty($object->parent),
        ];

        if ($object->get('childrenCount') < 1) {
            // Workaround for leaf record not to display "arrows"
            $objectArray['loaded'] = true;
        }

        return $objectArray;
    }

    /**
     * @param array $array
     * @param bool $count
     * @return string
     * @throws \xPDO\xPDOException
     */
    public function outputArray(array $array, $count = false)
    {
        return $this->modx->toJSON($array);
    }

}
