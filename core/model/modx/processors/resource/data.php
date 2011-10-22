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
if (!$modx->hasPermission('view')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('resource');

/* get resource */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('resource_err_ns'));
$c = $modx->newQuery('modResource');
$c->select(array(
    $modx->getSelectColumns('modResource','modResource'),
    'Template.templatename AS template_name',
    'CreatedBy.username AS creator',
    'EditedBy.username AS editor',
    'PublishedBy.username AS publisher',
));
$c->leftJoin('modTemplate','Template');
$c->leftJoin('modUser','CreatedBy');
$c->leftJoin('modUser','EditedBy');
$c->leftJoin('modUser','PublishedBy');
$c->where(array(
    'modResource.id' => $scriptProperties['id'],
));
$resource = $modx->getObject('modResource',$c);
if (empty($resource)) {
    return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $scriptProperties['id'])));
}
if (!$resource->checkPolicy('view')) return $modx->error->failure($modx->lexicon('permission_denied'));

$ra = $resource->toArray('',true,true);

/* format pub/unpub dates */
$emptyDate = '0000-00-00 00:00:00';
$ra['pub_date'] = !empty($ra['pub_date']) && $ra['pub_date'] != $emptyDate ? $ra['pub_date'] : $modx->lexicon('none');
$ra['unpub_date'] = !empty($ra['unpub_date']) && $ra['unpub_date'] != $emptyDate ? $ra['unpub_date'] : $modx->lexicon('none');
$ra['status'] = $ra['published'] ? $modx->lexicon('resource_published') : $modx->lexicon('resource_unpublished');

/* get changes */
$server_offset_time= intval($modx->getOption('server_offset_time',null,0));
$ra['createdon_adjusted'] = strftime('%c', strtotime($resource->get('createdon')) + $server_offset_time);
$ra['createdon_by'] = $resource->get('creator');
if (!empty($ra['editedon']) && $ra['editedon'] != $emptyDate) {
    $ra['editedon_adjusted'] = strftime('%c', strtotime($resource->get('editedon')) + $server_offset_time);
    $ra['editedon_by'] = $resource->get('editor');
} else {
    $ra['editedon_adjusted'] = $modx->lexicon('none');
    $ra['editedon_by'] = $modx->lexicon('none');
}
if (!empty($ra['publishedon']) && $ra['publishedon'] != $emptyDate) {
    $ra['publishedon_adjusted'] = strftime('%c', strtotime($resource->get('editedon')) + $server_offset_time);
    $ra['publishedon_by'] = $resource->get('publisher');
} else {
    $ra['publishedon_adjusted'] = $modx->lexicon('none');
    $ra['publishedon_by'] = $modx->lexicon('none');
}

/* template */
if (empty($ra['template_name'])) $ra['template_name'] = $modx->lexicon('empty_template');

/* source */
$resource->_contextKey= $resource->get('context_key');
$buffer = $modx->cacheManager->get($resource->getCacheKey(), array(
    xPDO::OPT_CACHE_KEY => $modx->getOption('cache_resource_key', null, 'resource'),
    xPDO::OPT_CACHE_HANDLER => $modx->getOption('cache_resource_handler', null, $modx->getOption(xPDO::OPT_CACHE_HANDLER)),
    xPDO::OPT_CACHE_FORMAT => (integer) $modx->getOption('cache_resource_format', null, $modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
));
if ($buffer) {
    $buffer = htmlspecialchars($buffer['resource']['_content']);
}
$ra['buffer'] = !empty($buffer) ? $buffer : $modx->lexicon('resource_notcached');

return $modx->error->success('',$ra);