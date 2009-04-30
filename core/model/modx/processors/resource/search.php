<?php
/**
 * Searches for specific resources and returns them in an array.
 *
 * @param integer $start The page to start on
 * @param integer $limit (optional) The number of results to limit by
 * @param string $sort The column to sort by
 * @param string $dir The direction to sort
 * @return array An array of modResources
 *
 * @package modx
 * @subpackage processors.resource
 */
$modx->lexicon->load('resource');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 20;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'pagetitle';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modResource');

$where = array();
if (isset($_REQUEST['id']) && $_REQUEST['id'] != '') {
    $where['id'] = $_REQUEST['id'];
}
if (isset($_REQUEST['pagetitle']) && $_REQUEST['pagetitle'] != '') {
    $where['pagetitle:LIKE'] = '%'.$_REQUEST['pagetitle'].'%';
}
if (isset($_REQUEST['longtitle']) && $_REQUEST['longtitle'] != '') {
    $where['longtitle:LIKE'] = '%'.$_REQUEST['longtitle'].'%';
}
if (isset($_REQUEST['content']) && $_REQUEST['content'] != '') {
    $where['content:LIKE'] = '%'.$_REQUEST['content'].'%';
}
if (isset($_REQUEST['published']) && $_REQUEST['published'] == 'on') {
    $where['published'] = true;
}
if (isset($_REQUEST['unpublished']) && $_REQUEST['unpublished'] == 'on') {
    $where['published'] = false;
}
if (isset($_REQUEST['deleted']) && $_REQUEST['deleted'] == 'on') {
    $where['deleted'] = true;
}
if (isset($_REQUEST['undeleted']) && $_REQUEST['undeleted'] == 'on') {
    $where['deleted'] = false;
}

$c->where($where);
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
$c->limit($_REQUEST['limit'],$_REQUEST['start']);
$resources = $modx->getCollection('modResource',$c);
$actions = $modx->request->getAllActionIDs();

$rs = array();
foreach ($resources as $resource) {
    if ($resource->checkPolicy('list')) {
        $ra = $resource->toArray();
        $ra['menu'] = array(
            array(
                'text' => $modx->lexicon('resource_edit'),
                'params' => array('a' => $actions['resource/update']),
            ),
        );
        $rs[] = $ra;
    }
}

return $this->outputArray($rs);