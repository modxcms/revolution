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
$resource->getOne('PublishedBy');
$resource->getOne('modTemplate');

$ra = $resource->toArray();

/* format pub/unpub dates */
$emptyDate = '0000-00-00 00:00:00';
$ra['pub_date'] = !empty($ra['pub_date']) && $ra['pub_date'] != $emptyDate ? $ra['pub_date'] : $modx->lexicon('none');
$ra['unpub_date'] = !empty($ra['unpub_date']) && $ra['unpub_date'] != $emptyDate ? $ra['unpub_date'] : $modx->lexicon('none');
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
} else {
    $ra['editedon_adjusted'] = $modx->lexicon('none');
    $ra['editedon_by'] = $modx->lexicon('none');
}
if (!empty($ra['publishedon']) && $resource->PublishedBy) {
    $ra['publishedon_adjusted'] = strftime('%c', strtotime($resource->get('editedon')) + $server_offset_time);
    $ra['publishedon_by'] = $resource->PublishedBy->get('username');
} else {
    $ra['publishedon_adjusted'] = $modx->lexicon('none');
    $ra['publishedon_by'] = $modx->lexicon('none');
}

/* template */
$ra['template'] = $resource->modTemplate ? $resource->modTemplate->get('templatename') : $modx->lexicon('empty_template');

/* source */
$resource->_contextKey= $resource->get('context_key');
if ($buffer = $modx->cacheManager->get($resource->getCacheKey())) {
    $buffer = htmlspecialchars($buffer['resource']['_content']);
}
$ra['buffer'] = !empty($buffer) ? $buffer : $modx->lexicon('resource_notcached');


/* set none to blank stuff */
foreach ($ra as $k => $v) {
    if (empty($ra[$k])) {
        $ra[$k] = $modx->lexicon('none');
    }
}

return $modx->error->success('',$ra);