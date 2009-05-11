<?php
/**
 * Grabs the site schedule data.
 *
 * @param string $mode pub_date|unpub_date (optional) The mode to grab, either
 * to-publish or to-unpublish. Defaults to pub_date.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.resource.event
 */
$modx->lexicon->load('resource');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';
if (!isset($_REQUEST['mode'])) $_REQUEST['mode'] = 'pub_date';

$c = $modx->newQuery('modResource');
$c->where(array(
    $_REQUEST['mode'].':>' => time(),
));
$c->sortby($_REQUEST['mode'],$_REQUEST['dir']);
$c->limit($_REQUEST['limit'],$_REQUEST['start']);
$publish = $modx->getCollection('modResource',$c);

$cc = $modx->newQuery('modResource');
$cc->where(array(
    $_REQUEST['mode'].':>' => time(),
));
$count = $modx->getCollection('modResource',$cc);

$ps = array();
$time_format = '%a %b %d, %Y';
foreach ($publish as $resource) {
    $pa = $resource->toArray();

    if ($resource->get('pub_date') != '') {
        $pd = $resource->get('pub_date')+$modx->getOption('server_offset_time',null,0);
        $pa['pub_date'] = strftime($time_format,$pd);
    }

    if ($resource->get('unpub_date') != '') {
        $pd = $resource->get('unpub_date')+$modx->getOption('server_offset_time',null,0);
        $pa['unpub_date'] = strftime($time_format,$pd);
    }
    $ps[] = $pa;
}

return $this->outputArray($ps,$count);