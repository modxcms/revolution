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
$limit = 0;
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$name = $modx->getOption('name',$scriptProperties,false);
$plugin = $modx->getOption('plugin',$scriptProperties,false);

$criteria = array();
if (!empty($name)) {
    $criteria[] = array('name:LIKE' => '%'.$name.'%');
}

$c = $modx->newQuery('modEvent');

$eventsResult = $modx->call('modEvent', 'listEvents', array(&$modx, $plugin, $criteria, array($sort=> $dir), $limit, $start));
$count = $eventsResult['count'];
$events = $eventsResult['collection'];

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