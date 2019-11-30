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
use MODX\Revolution\Processors\Processor;

/**
 * Sort menu items for a tree
 * @param string $data
 * @package MODX\Revolution\Processors\System\Menu
 */
class Sort extends Processor
{
    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('menus');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['action', 'menu'];
    }

    /**
     * @return array|mixed|string
     * @throws \xPDO\xPDOException
     */
    public function process()
    {
        $data = $this->getProperty('data');
        if (empty($data)) {
            return $this->failure();
        }
        $data = urldecode($data);
        $data = $this->modx->fromJSON($data);

        $nodes = [];
        $this->getNodesFormatted($nodes, $data);
        $this->sort($nodes);

        $cacheManager = $this->modx->getCacheManager();
        $cacheManager->refresh([
            'menu' => [],
        ]);
        return $this->success();
    }

    /**
     * @param $ar_nodes
     * @param $cur_level
     * @param string $parent
     */
    public function getNodesFormatted(&$ar_nodes, $cur_level, $parent = '')
    {
        $order = 0;
        foreach ($cur_level as $id => $children) {
            $id = preg_replace('/n_/', '', $id, 1);
            $ar_nodes[] = [
                'text' => $id,
                'parent' => $parent,
                'order' => $order,
            ];
            $order++;
            $this->getNodesFormatted($ar_nodes, $children, $id);
        }
    }

    /**
     * @param $nodes
     */
    public function sort($nodes)
    {
        /* readjust cache */
        foreach ($nodes as $node) {
            $menu = $this->modx->getObject(modMenu::class, $node['text']);
            if ($menu === null) {
                continue;
            }

            if ($menu->get('parent') !== $node['parent']) {
                /* get new parent, if invalid, skip, unless is root */
                if (!empty($node['parent'])) {
                    $parentMenu = $this->modx->getObject(modMenu::class, $node['parent']);
                    if ($parentMenu === null) {
                        continue;
                    }
                }

                /* save new parent */
                $menu->set('parent', $node['parent']);
            }
            $menu->set('menuindex', $node['order']);
            $menu->save();
        }
    }

}
