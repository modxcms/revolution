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
$c->select('
    Namespace.*,
    COUNT(Actions.id) AS `actionCount`
');
$c->leftJoin('modAction','Actions');
$c->sortby('name','ASC');
$c->groupby('Namespace.name');
$namespaces = $modx->getCollection('modNamespace',$c);

foreach ($namespaces as $namespace) {
    $menu = array();
    $menu[] = array(
        'text' => $modx->lexicon('action_create_here'),
        'handler' => 'function(itm,e) { this.createAction(itm,e); }',
    );
    $list[] = array(
        'text' => $namespace->get('name'),
        'id' => 'n_namespace_'.$namespace->get('name'),
        'leaf' => $namespace->get('actionCount') <= 0,
        'cls' => 'icon-namespace',
        'pk' => $namespace->get('name'),
        'data' => $namespace->toArray(),
        'type' => 'namespace',
        'menu' => array('items' => $menu),
    );
}
return $list;