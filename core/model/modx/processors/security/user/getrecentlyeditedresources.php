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
$modx->lexicon->load('resource','user');

$limit = !empty($_REQUEST['limit']);
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'editedon';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'DESC';

/* get user */
if (!isset($_REQUEST['user'])) return $modx->error->failure($modx->lexicon('user_err_ns'));
$user = $modx->getObject('modUser',$_REQUEST['user']);
if ($user == null) return $modx->error->failure($modx->lexicon('user_err_not_found'));

/* get resources */
$c = $modx->newQuery('modResource');
$c->where(array('editedby' => $user->get('id')));
$c->orCondition(array('createdby' => $user->get('id')));
$count= $modx->getCount('modResource',$c);

$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if ($limit) $c->limit($_REQUEST['limit'],$_REQUEST['start']);

$resources= $modx->getCollection('modResource',$c);

/* process into array */
$actions = $modx->request->getAllActionIDs();
$rs = array();
foreach ($resources as $resource) {
    $ra = $resource->toArray();
    $ra['menu'] = array(
        array(
            'text' => $modx->lexicon('resource_view'),
            'params' => array(
                'a' => $actions['resource/data'],
                'id' => $resource->get('id'),
            ),
        ),
        array(
            'text' => $modx->lexicon('resource_edit'),
            'params' => array(
                'a' => $actions['resource/update'],
                'id' => $resource->get('id'),
            ),
        ),
        '-',
        array(
            'text' => $modx->lexicon('resource_preview'),
            'handler' => 'this.preview',
        ),
    );
    $rs[] = $ra;
}
return $this->outputArray($rs,$count);