<?php
/**
 * Returns resource data.
 *
 * @param integer $id The ID of the resource
 * @return array
 *
 * @package modx
 * @subpackage processors.resource
 */
$modx->lexicon->load('resource');

/* get resource */
if (!isset($_REQUEST['id'])) {
    return $modx->error->failure($modx->lexicon('resource_err_ns'));
}
$resource = $modx->getObject('modResource', $_REQUEST['id']);
if ($resource == null) {
    return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $_REQUEST['id'])));
}
if (!$resource->checkPolicy('view')) return $modx->error->failure($modx->lexicon('permission_denied'));

$resource->getOne('CreatedBy');
$resource->getOne('EditedBy');
$resource->getOne('modTemplate');

$ra = $resource->toArray();

/* format pub/unpub dates */
$ra['pub_date'] = $ra['pub_date'] != '0' ? $ra['pub_date'] : '';
$ra['unpub_date'] = $ra['unpub_date'] != '0' ? $ra['unpub_date'] : '';
$ra['status'] = $ra['published'] ? $modx->lexicon('resource_published') : $modx->lexicon('resource_unpublished');

/* keywords */
$dkws = $resource->getMany('modResourceKeyword');
$resource->keywords = array();
foreach ($dkws as $dkw) {
    $resource->keywords[$dkw->get('keyword_id')] = $dkw->getOne('modKeyword');
}
$keywords = array();
foreach ($resource->keywords as $kw) {
    $keywords[] = $kw->get('keyword');
}
$ra['keywords'] = join($keywords,',');

/* get changes */
$server_offset_time= intval($modx->getOption('server_offset_time',null,0));
$ra['createdon_adjusted'] = strftime('%c', strtotime($resource->get('createdon')) + $server_offset_time);
$ra['createdon_by'] = $resource->CreatedBy->get('username');
if ($resource->EditedBy) {
    $ra['editedon_adjusted'] = strftime('%c', strtotime($resource->get('editedon')) + $server_offset_time);
    $ra['editedon_by'] = $resource->EditedBy->get('username');
}

/* template */
$ra['template'] = $resource->modTemplate ? $resource->modTemplate->get('templatename') : '-';

/* source */
$resource->_contextKey= $resource->get('context_key');
if ($buffer = $modx->cacheManager->get($resource->getCacheKey())) {
    $buffer = htmlspecialchars($buffer['resource']['_content']);
}
$ra['buffer'] = !empty($buffer) ? $buffer : $modx->lexicon('resource_notcached');

return $modx->error->success('',$ra);