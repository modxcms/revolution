<?php
/**
 * Gets a list of namespaces for action nodes
 *
 * @package modx
 * @subpackage processors.system.action
 */
$list = array();

$c = $modx->newQuery('modNamespace');
$c->select('
    `modNamespace`.*,
    COUNT(`modAction`.`id`) AS `actionCount`
');
$c->leftJoin('modAction','modAction');
$c->sortby('name','ASC');
$c->groupby('modNamespace.name');
$namespaces = $modx->getCollection('modNamespace',$c);

foreach ($namespaces as $namespace) {
    $list[] = array(
        'text' => $namespace->get('name'),
        'id' => 'n_namespace_'.$namespace->get('name'),
        'leaf' => $namespace->get('actionCount') <= 0,
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
return $list;