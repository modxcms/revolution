<?php
/**
 * Gets a list of namespaces for action nodes
 *
 * @package modx
 * @subpackage processors.system.action
 */
$list = array();

$c = $modx->newQuery('modNamespace');
$c->select($modx->getSelectColumns('modNamespace','modNamespace'));
$c->select(array(
    'COUNT('.$modx->getSelectColumns('modAction','Actions','',array('id')).') AS '.$modx->escape('actionCount'),
));
$c->leftJoin('modAction','Actions');
$nameField = $modx->getSelectColumns('modNamespace','modNamespace','',array('name', 'path'));
$c->sortby($nameField,'ASC');
$c->groupby($nameField);
$namespaces = $modx->getIterator('modNamespace',$c);
unset($nameField);

foreach ($namespaces as $namespace) {
    $menu = array();
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