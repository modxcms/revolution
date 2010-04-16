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
$plugin = $modx->getOption('plugin',$scriptProperties,false);

/* query for events */
$c = $modx->newQuery('modEvent');
if ($name) $c->where(array('name:LIKE' => '%'.$name.'%'));
if ($plugin) {
    $c->leftJoin('modPluginEvent','modPluginEvent','
        `modPluginEvent`.`event` = `modEvent`.`name`
    AND `modPluginEvent`.`pluginid` = '.$plugin.'
    ');
    $c->select('
        `modEvent`.*,
        IF(ISNULL(`modPluginEvent`.`pluginid`),0,1) AS `enabled`,
        `modPluginEvent`.`priority` AS `priority`,
        `modPluginEvent`.`propertyset` AS `propertyset`
    ');
}
$count = $modx->getCount('modEvent',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$events = $modx->getCollection('modEvent',$c);

/* iterate through events */
$list = array();
foreach ($events as $event) {
    $eventArray = $event->toArray();
    $eventArray['enabled'] = $event->get('enabled') ? 1 : 0;

    $eventArray['menu'] = array(
        array(
            'text' => $modx->lexicon('plugin_event_update'),
            'handler' => 'this.updateEvent',
        )
    );
    $list[] = $eventArray;
}
return $this->outputArray($list,$count);