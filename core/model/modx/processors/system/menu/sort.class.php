<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Sort menu items for a tree
 *
 * @param json $data
 *
 * @package modx
 * @subpackage processors.system.menu
 */

class modMenuSortProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('menus');
    }
    public function getLanguageTopics() {
        return array('action','menu');
    }

    public function process() {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->failure();
        $data = urldecode($data);
        $data = $this->modx->fromJSON($data);

        $nodes = array();
        $this->getNodesFormatted($nodes,$data);
        $this->sort($nodes);

        $cacheManager = $this->modx->getCacheManager();
        $cacheManager->refresh(array(
            'menu' => array(),
        ));
        return $this->success();
    }

    public function getNodesFormatted(&$ar_nodes,$cur_level,$parent = '') {
        $order = 0;
        foreach ($cur_level as $id => $children) {
            $id = preg_replace('/n_/', '', $id, 1);
            $ar_nodes[] = array(
                'text' => $id,
                'parent' => $parent,
                'order' => $order,
            );
            $order++;
            $this->getNodesFormatted($ar_nodes,$children,$id);
        }
    }

    public function sort($nodes) {
        /* readjust cache */
        foreach ($nodes as $node) {
            $menu = $this->modx->getObject('modMenu',$node['text']);
            if (empty($menu)) continue;

            if ($menu->get('parent') != $node['parent']) {
                /* get new parent, if invalid, skip, unless is root */
                if (!empty($node['parent'])) {
                    $parentMenu = $this->modx->getObject('modMenu',$node['parent']);
                    if (empty($parentMenu)) continue;
                }

                /* save new parent */
                $menu->set('parent',$node['parent']);
            }
            $menu->set('menuindex',$node['order']);
            $menu->save();
        }
    }

}
return 'modMenuSortProcessor';
