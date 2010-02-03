<?php
/**
 * Gets all subactions for an action
 *
 * @package modx
 * @subpackage processors.system.action
 */
$list = array();

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
    $menu = array();
    $menu[] = array(
        'text' => $modx->lexicon('action_update'),
        'handler' => 'function(itm,e) {
            this.updateAction(itm,e);
        }',
    );
    $menu[] = '-';
    $menu[] = array(
        'text' => $modx->lexicon('action_create_here'),
        'handler' => 'function(itm,e) {
            this.createAction(itm,e);
        }',
    );
    $menu[] = '-';
    $menu[] = array(
        'text' => $modx->lexicon('action_remove'),
        'handler' => 'function(itm,e) {
            this.removeAction(itm,e);
        }',
    );

    $list[] = array(
        'text' => $action->get('controller').' ('.$action->get('id').')',
        'id' => 'n_action_'.$action->get('id'),
        'pk' => $action->get('id'),
        'leaf' => $action->get('childrenCount') <= 0,
        'cls' => 'icon-action',
        'type' => 'action',
        'data' => $action->toArray(),
        'menu' => array('items' => $menu),
    );
}
return $list;