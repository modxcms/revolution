<?php
/**
 * Grabs the actions in node format
 *
 * @param string $id The parent node ID
 *
 * @package modx
 * @subpackage processors.system.action
 */
class modActionGetNodesProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('actions');
    }
    public function getLanguageTopics() {
        return array('action','menu','namespace');
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function initialize() {
        $this->setDefaultProperties(array(
            'limit' => 10,
            'start' => 0,
            'id' => 'n_0',
        ));
        return true;
    }

    public function process() {
        $map = $this->getMap();
        if (empty($map)) return $this->failure();

        switch ($map['type']) {
            case 'namespace':
                $list = $this->getNodesInNamespace($map);
                break;
            case 'root':
            default:
                $list = $this->getRootNodes($map);
                break;
        }
        return $this->toJSON($list);
    }

    /**
     * @return array
     */
    public function getMap() {
        $id = $this->getProperty('id');
        if (empty($id)) return array();
        
        $ar = explode('_',$id);
        return array(
            'type' => $ar[1],
            'id' => $ar[2],
        );
    }

    public function getRootNodes(array $map) {
        $list = array();

        $c = $this->modx->newQuery('modNamespace');
        $c->select($this->modx->getSelectColumns('modNamespace','modNamespace'));
        $c->select(array(
            'COUNT('.$this->modx->getSelectColumns('modAction','Actions','',array('id')).') AS '.$this->modx->escape('actionCount'),
        ));
        $c->leftJoin('modAction','Actions');
        $nameField = $this->modx->getSelectColumns('modNamespace','modNamespace','',array('name', 'path'));
        $c->sortby($nameField,'ASC');
        $c->groupby($nameField);
        $namespaces = $this->modx->getIterator('modNamespace',$c);

        /** @var modNamespace $namespace */
        foreach ($namespaces as $namespace) {
            $list[] = array(
                'text' => $namespace->get('name'),
                'id' => 'n_namespace_'.$namespace->get('name'),
                'leaf' => $namespace->get('actionCount') <= 0,
                'cls' => 'icon-namespace',
                'pk' => $namespace->get('name'),
                'data' => $namespace->toArray(),
                'type' => 'namespace',
            );
        }
        return $list;
    }

    /**
     * Get all Actions in a Namespace
     * @param array $map
     * @return array
     */
    public function getNodesInNamespace(array $map) {
        $list = array();

        $c = $this->modx->newQuery('modAction');
        $modActionCols = $this->modx->getSelectColumns('modAction','modAction');
        $c->select($modActionCols);
        $c->where(array(
            'modAction.namespace' => $map['id'],
        ));
        $c->groupby($modActionCols);
        $c->sortby('modAction.controller','ASC');
        $actions = $this->modx->getIterator('modAction',$c);

        /** @var modAction $action */
        foreach ($actions as $action) {
            $list[] = array(
                'text' => $action->get('controller').' ('.$action->get('id').')',
                'id' => 'n_action_'.$action->get('id'),
                'pk' => $action->get('id'),
                'leaf' => true,
                'cls' => 'icon-action',
                'type' => 'action',
                'data' => $action->toArray(),
            );
        }
        return $list;
    }
}
return 'modActionGetNodesProcessor';