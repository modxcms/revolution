<?php

namespace MODX\Processors\System\Action;

use MODX\Processors\modProcessor;

/**
 * Sorts actions from a tree
 *
 * @package modx
 * @subpackage processors.system.action
 */
class Sort extends modProcessor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('actions');
    }


    public function getLanguageTopics()
    {
        return ['action', 'menu', 'namespace'];
    }


    public function process()
    {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->failure();
        $data = urldecode($data);
        $data = json_decode($data, true);
        $nodes = [];
        $this->getNodesFormatted($nodes, $data);

        return $this->success();
    }


    public function getNodesFormatted(&$ar_nodes, $cur_level, $parent = 0)
    {
        $order = 0;
        foreach ($cur_level as $id => $children) {
            $id = explode('_', $id);
            $id = $id[1];
            $ar_nodes[] = [
                'id' => $id,
                'parent' => $parent,
                'order' => $order,
            ];
            $order++;
            $this->getNodesFormatted($ar_nodes, $children, $id);
        }
    }

}