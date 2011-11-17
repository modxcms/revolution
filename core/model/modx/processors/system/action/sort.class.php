<?php
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

        /* readjust cache */
        foreach ($nodes as $ar_node) {
            /** @var modAction $action */
            $action = $this->modx->getObject('modAction',$ar_node['id']);
            if (empty($action)) continue;
            $old_parent_id = $action->get('parent');

            if ($old_parent_id != $ar_node['parent']) {
                /* get new parent, if invalid, skip, unless is root */
                if (!empty($ar_node['parent'])) {
                    $parent = $this->modx->getObject('modAction',$ar_node['parent']);
                    if (empty($parent)) continue;
                }

                /* save new parent */
                $action->set('parent',$ar_node['parent']);
                $action->save();
            }
        }

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