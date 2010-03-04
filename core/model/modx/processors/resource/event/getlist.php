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
if (!$modx->hasPermission('view_document')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('resource');

/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$mode = $modx->getOption('mode',$scriptProperties,'pub_date');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');

$c = $modx->newQuery('modResource');
$c->where(array(
    $scriptProperties['mode'].':>' => time(),
));
$count = $modx->getCount('modResource',$c);
$c->sortby($mode,$dir);
$c->limit($limit,$start);
$resources = $modx->getCollection('modResource',$c);

$list = array();
$timeFormat = '%a %b %d, %Y';
$offset = $modx->getOption('server_offset_time',null,0) * 60 * 60;
foreach ($resources as $resource) {
    if (!$resource->checkPolicy('view')) continue;

    $resourceArray = $resource->toArray();
    unset($resourceArray['content']);

    if ($resource->get('pub_date') != '') {
        $pubDate = strtotime($resource->get('pub_date'))+$offset;
        $resourceArray['pub_date'] = strftime($timeFormat,$pubDate);
    }

    if ($resource->get('unpub_date') != '') {
        $unpubDate = strtotime($resource->get('unpub_date'))+$offset;
        $resourceArray['unpub_date'] = strftime($timeFormat,$unpubDate);
    }
    $list[] = $resourceArray;
}

return $this->outputArray($list,$count);