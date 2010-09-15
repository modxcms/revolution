<?php
/**
 * Gets a list of namespaces for action nodes
 *
 * @package modx
 * @subpackage processors.system.action
 */
$list = array();

$c = $modx->newQuery('modNamespace');
$c->setClassAlias('Namespace');
$c->select(array(
    'Namespace.*',
    'COUNT(`Actions`.`id`) AS `actionCount`',
));
$c->leftJoin('modAction','Actions');
$nameField = $modx->getSelectColumns('modNamespace','Namespace','',array('name'));
$c->sortby($nameField,'ASC');
$c->groupby($nameField);
$namespaces = $modx->getCollection('modNamespace',$c);
//$sql = $c->toSql(); echo $sql; die();
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