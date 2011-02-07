<?php
/**
 * Gets all action nodes for a namespace
 *
 * @package modx
 * @subpackage processors.system.action
 */
$list = array();

$c = $modx->newQuery('modAction');
$c->leftJoin('modAction','Children');
$modActionCols = $modx->getSelectColumns('modAction','modAction');
$c->select($modActionCols);
$c->select(array(
    'COUNT('.$modx->escape('Children').'.'.$modx->escape('id').') AS '.$modx->escape('childrenCount'),
));
$c->where(array(
    'modAction.parent' => 0,
    'modAction.namespace' => $pk,
));
$c->groupby($modActionCols);
$c->sortby('modAction.controller','ASC');
$actions = $modx->getIterator('modAction',$c);

foreach ($actions as $action) {
    $list[] = array(
        'text' => $action->get('controller').' ('.$action->get('id').')',
        'id' => 'n_action_'.$action->get('id'),
        'pk' => $action->get('id'),
        'leaf' => $action->get('childrenCount') <= 0,
        'cls' => 'icon-action',
        'type' => 'action',
        'data' => $action->toArray(),
    );
}
return $list;