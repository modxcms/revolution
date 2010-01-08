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
if (!$modx->hasPermission('list')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('resource');

/* setup default properties */
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,10);
$sort = $modx->getOption('sort',$_REQUEST,'pagetitle');
$dir = $modx->getOption('dir',$_REQUEST,'ASC');

/* setup query */
$c = $modx->newQuery('modResource');
$where = array();
if (!empty($_REQUEST['id'])) $where['id'] = $_REQUEST['id'];
if (!empty($_REQUEST['pagetitle'])) $where['pagetitle:LIKE'] = '%'.$_REQUEST['pagetitle'].'%';
if (!empty($_REQUEST['longtitle'])) $where['longtitle:LIKE'] = '%'.$_REQUEST['longtitle'].'%';
if (!empty($_REQUEST['content'])) $where['content:LIKE'] = '%'.$_REQUEST['content'].'%';

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
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
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