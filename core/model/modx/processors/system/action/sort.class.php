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
 * Sorts actions from a tree
 *
 * @package modx
 * @subpackage processors.system.action
 */
class modActionSortProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('actions');
    }
    public function getLanguageTopics() {
        return array('action','menu','namespace');
    }

    public function process() {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->failure();
        $data = urldecode($data);
        $data = $this->modx->fromJSON($data);
        $nodes = array();
        $this->getNodesFormatted($nodes,$data);

        return $this->success();
    }

    public function getNodesFormatted(&$ar_nodes,$cur_level,$parent = 0) {
        $order = 0;
        foreach ($cur_level as $id => $children) {
            $id = explode('_',$id);
            $id = $id[1];
            $ar_nodes[] = array(
                'id' => $id,
                'parent' => $parent,
                'order' => $order,
            );
            $order++;
            $this->getNodesFormatted($ar_nodes,$children,$id);
        }
    }

}
return 'modActionSortProcessor';
