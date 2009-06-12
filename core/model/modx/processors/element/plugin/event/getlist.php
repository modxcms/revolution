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

$c = $modx->newQuery('modEvent');
if (!empty($_REQUEST['name'])) {
     $c->where(array('name:LIKE' => '%'.$_REQUEST['name'].'%'));
}
if (!empty($_REQUEST['plugin'])) {
    $c->leftJoin('modPluginEvent','modPluginEvent','
        modPluginEvent.evtid = modEvent.id
    AND modPluginEvent.pluginid = '.$_REQUEST['plugin'].'
    ');
    $c->select('modEvent.*,
        IF(ISNULL(modPluginEvent.pluginid),0,1) AS enabled,
        modPluginEvent.priority AS priority,
        modPluginEvent.propertyset AS propertyset
    ');
}
$count = $modx->getCount('modEvent',$cc);

$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if ($limit) $c->limit($_REQUEST['limit'],$_REQUEST['start']);
$events = $modx->getCollection('modEvent',$c);

$es = array();
foreach ($events as $event) {
    $ea = $event->toArray();
    $ea['enabled'] = $event->get('enabled') ? 1 : 0;

    $es[] = $ea;
}
return $this->outputArray($es,$count);