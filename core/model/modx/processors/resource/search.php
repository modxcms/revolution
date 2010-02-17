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
if (!$modx->hasPermission('view_document')) return $modx->error->failure($modx->lexicon('permission_denied'));
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

if (!empty($_REQUEST['published'])) $where['published'] = true;
if (!empty($_REQUEST['unpublished'])) $where['published'] = false;
if (!empty($_REQUEST['deleted'])) $where['deleted'] = true;
if (!empty($_REQUEST['undeleted'])) $where['deleted'] = false;

$c->where($where);
$count = $modx->getCount('modResource',$c);
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$resources = $modx->getCollection('modResource',$c);
$actions = $modx->request->getAllActionIDs();

/* iterate */
$list = array();
foreach ($resources as $resource) {
    if ($resource->checkPolicy('list')) {
        $resourceArray = $resource->toArray();
        $resourceArray['menu'] = array();
        if ($modx->hasPermission('edit_document')) {
            $resourceArray['menu'][] = array(
                'text' => $modx->lexicon('resource_edit'),
                'params' => array('a' => $actions['resource/update']),
            );
        }
        $list[] = $resourceArray;
    }
}

return $this->outputArray($list,$count);