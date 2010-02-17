<?php
/**
 * Gets a list of recently edited resources by a user
 *
 * @param integer $user The user to grab from
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.security.user
 */
if (!$modx->hasPermission(array('view_user' => true,'view' => true))) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('resource','user');

/* setup default properties */
$isLimit = !empty($_REQUEST['limit']);
$start = $modx->getOption('start',$_REQUEST,0);
$limit = $modx->getOption('limit',$_REQUEST,10);
$sort = $modx->getOption('sort',$_REQUEST,'editedon');
$dir = $modx->getOption('dir',$_REQUEST,'DESC');

/* get user */
if (empty($_REQUEST['user'])) return $modx->error->failure($modx->lexicon('user_err_ns'));
$user = $modx->getObject('modUser',$_REQUEST['user']);
if (empty($user)) return $modx->error->failure($modx->lexicon('user_err_not_found'));

/* get resources */
$c = $modx->newQuery('modResource');
$c->where(array(
    'editedby' => $user->get('id'),
    'OR:createdby:=' => $user->get('id'),
));
$count= $modx->getCount('modResource',$c);
$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$resources = $modx->getCollection('modResource',$c);

/* iterate */
$actions = $modx->request->getAllActionIDs();
$list = array();
foreach ($resources as $resource) {
    if (!$resource->checkPolicy('view')) continue;

    $resourceArray = $resource->get(array('id','pagetitle','description','published','deleted'));
    $resourceArray['menu'] = array();
    $resourceArray['menu'][] = array(
        'text' => $modx->lexicon('resource_view'),
        'params' => array(
            'a' => $actions['resource/data'],
            'id' => $resource->get('id'),
        ),
    );
    if ($modx->hasPermission('edit_document')) {
        $resourceArray['menu'][] = array(
            'text' => $modx->lexicon('resource_edit'),
            'params' => array(
                'a' => $actions['resource/update'],
                'id' => $resource->get('id'),
            ),
        );
    }
    $resourceArray['menu'][] = '-';
    $resourceArray['menu'][] = array(
        'text' => $modx->lexicon('resource_preview'),
        'handler' => 'this.preview',
    );
    $list[] = $resourceArray;
}
return $this->outputArray($list,$count);