<?php
/**
 * Gets a list of system events
 *
 * @package modx
 * @subpackage processors.element.plugin.event
 */
if (!$modx->hasPermission('view_plugin')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('plugin','system_events');

/* setup default properties */
$isLimit = empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$name = $modx->getOption('name',$scriptProperties,false);
$event = $modx->getOption('event',$scriptProperties,false);

$c = $modx->newQuery('modPlugin');
if (!empty($name)) {
    $c->where(array('name:LIKE' => '%'.$name.'%'));
}
if (!empty($event)) {
    $c->innerJoin('modPluginEvent','modPluginEvent',array(
        'modPluginEvent.pluginid = modPlugin.id',
        'modPluginEvent.event' => $event,
    ));
    $c->select($modx->getSelectColumns('modPlugin','modPlugin'));
    $c->select(array(
        'modPluginEvent.priority',
        'modPluginEvent.pluginid',
        'modPluginEvent.propertyset',
    ));
}
$count = $modx->getCount('modPlugin',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$plugins = $modx->getCollection('modPlugin',$c);

$list = array();
foreach ($plugins as $plugin) {
    $pluginArray = $plugin->toArray();

    $list[] = array(
        $pluginArray['id'],
        $pluginArray['name'],
        $pluginArray['priority'],
        $pluginArray['propertyset'],
    );
}
return $modx->error->success('',$list);