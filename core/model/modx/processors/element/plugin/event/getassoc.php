<?php
/**
 * Gets a list of system events
 *
 * @package modx
 * @subpackage processors.element.plugin.event
 */
$modx->lexicon->load('plugin','system_events');

if (!$modx->hasPermission('view')) return $modx->error->failure($modx->lexicon('permission_denied'));

$limit = !empty($_REQUEST['limit']);
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modPlugin');
if (!empty($_REQUEST['name'])) {
     $c->where(array('name:LIKE' => '%'.$_REQUEST['name'].'%'));
}

if (!empty($_REQUEST['event'])) {
    $c->innerJoin('modPluginEvent','modPluginEvent','
        modPluginEvent.pluginid = modPlugin.id
    AND modPluginEvent.evtid = '.$_REQUEST['event'].'
    ');
    $c->select('modPlugin.*,
        IF(ISNULL(modPluginEvent.pluginid),0,1) AS enabled,
        modPluginEvent.priority AS priority,
        modPluginEvent.propertyset AS propertyset
    ');
}
$count = $modx->getCount('modPlugin',$c);

$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if ($limit) $c->limit($_REQUEST['limit'],$_REQUEST['start']);
$plugins = $modx->getCollection('modPlugin',$c);

$list = array();
foreach ($plugins as $plugin) {
    $pluginArray = $plugin->toArray();

    $list[] = array(
        $pluginArray['id'],
        $pluginArray['name'],
        $pluginArray['priorityy'],
        $pluginArray['propertyset'],
        array(),
    );
}
return $modx->error->success('',$list);