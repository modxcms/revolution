<?php
/**
 * Grabs the actions in node format
 *
 * @param string $id The parent node ID
 *
 * @package modx
 * @subpackage processors.system.action
 */
$modx->lexicon->load('action','menu');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['id'])) $_REQUEST['id'] = 'n_0';

$ar = explode('_',$_REQUEST['id']);
$type = $ar[1];
$pk = $ar[2];

$list = array();
/* contexts */
if ($type == 'root') {
    $c = $modx->newQuery('modNamespace');
    $c->sortby('name','ASC');
    $namespaces = $modx->getCollection('modNamespace',$c);

    foreach ($namespaces as $namespace) {
        $list[] = array(
            'text' => $namespace->get('name'),
            'id' => 'n_namespace_'.$namespace->get('name'),
            'leaf' => false,
            'cls' => 'folder',
            'pk' => $namespace->get('name'),
            'data' => $namespace->toArray(),
            'type' => 'namespace',
            'menu' => array( 'items' => array(
                array(
                    'text' => $modx->lexicon('action_create_here'),
                    'handler' => 'function(itm,e) {
                        this.createAction(itm,e);
                    }',
                ),
            )),
        );
    }

/* root actions */
} else if ($type == 'namespace') {
    $c = $modx->newQuery('modAction');
    $c->leftJoin('modAction','Children');
    $c->select('
        `modAction`.*,
        COUNT(`Children`.`id`) AS `childrenCount`
    ');
    $c->where(array(
        'modAction.parent' => 0,
        'modAction.namespace' => $pk,
    ));
    $c->groupby('modAction.id');
    $c->sortby('modAction.controller','ASC');

    $actions = $modx->getCollection('modAction',$c);

    foreach ($actions as $action) {
        $list[] = array(
            'text' => $action->get('controller').' ('.$action->get('id').')',
            'id' => 'n_action_'.$action->get('id'),
            'pk' => $action->get('id'),
            'leaf' => $action->get('childrenCount') <= 0,
            'cls' => 'action',
            'type' => 'action',
            'data' => $action->toArray(),
            'menu' => array(
                'items' => array(
                    array(
                        'text' => $modx->lexicon('action_update'),
                        'handler' => 'function(itm,e) {
                            this.updateAction(itm,e);
                        }',
                    ),'-',array(
                        'text' => $modx->lexicon('action_create_here'),
                        'handler' => 'function(itm,e) {
                            this.createAction(itm,e);
                        }',
                    ),'-',array(
                        'text' => $modx->lexicon('action_remove'),
                        'handler' => 'function(itm,e) {
                            this.removeAction(itm,e);
                        }',
                    ),
                ),
            ),
        );
    }

/* subactions */
} else {
    $c = $modx->newQuery('modAction');
    $c->leftJoin('modAction','Children');
    $c->select('
        `modAction`.*,
        COUNT(`Children`.`id`) AS `childrenCount`
    ');
    $c->where(array(
        'modAction.parent' => $pk,
    ));
    $c->groupby('modAction.id');
    $c->sortby('modAction.controller','ASC');

    $actions = $modx->getCollection('modAction',$c);

    foreach ($actions as $action) {
        $list[] = array(
            'text' => $action->get('controller').' ('.$action->get('id').')',
            'id' => 'n_action_'.$action->get('id'),
            'pk' => $action->get('id'),
            'leaf' => $action->get('childrenCount') <= 0,
            'cls' => 'action',
            'type' => 'action',
            'data' => $action->toArray(),
            'menu' => array(
                'items' => array(
                    array(
                        'text' => $modx->lexicon('action_update'),
                        'handler' => 'function(itm,e) {
                            this.updateAction(itm,e);
                        }',
                    ),'-',array(
                        'text' => $modx->lexicon('action_create_here'),
                        'handler' => 'function(itm,e) {
                            this.createAction(itm,e);
                        }',
                    ),'-',array(
                        'text' => $modx->lexicon('action_remove'),
                        'handler' => 'function(itm,e) {
                            this.removeAction(itm,e);
                        }',
                    ),
                ),
            ),
        );
    }
}


return $this->toJSON($list);
