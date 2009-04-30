<?php
/**
 * Loads the system event detail page
 *
 * @package modx
 * @subpackage manager.system.event
 */
if(!$modx->hasPermission('view_eventlog')) return $modx->error->failure($modx->lexicon('access_denied'));

$event = $modx->getObject('modEventLog',$_REQUEST['id']);
if ($event == null) return $modx->error->failure('Event not found!');

$event->user = $modx->getObject('modUser', $event->get('user'));

$modx->smarty->assign('event',$event);
return $modx->smarty->fetch('system/event/details.tpl');