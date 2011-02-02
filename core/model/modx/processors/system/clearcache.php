<?php
/**
 * Refreshes the site cache
 *
 * @package modx
 * @subpackage system
 */
if (!$modx->hasPermission('empty_cache')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* invoke OnBeforeCacheUpdate event */
$modx->invokeEvent('OnBeforeCacheUpdate');

$paths = array();
$partitions = array();
if ($modx->getOption('cache_db', null, false)) {
    $partitions['db'] = array();
}

$contextKeys = isset($scriptProperties['contexts']) ? explode(',', $scriptProperties['contexts']) : array();
if (!empty($contextKeys)) {
    array_walk($contextKeys, 'trim');
} else {
    $contexts = array();
    $contextCollection = $modx->getCollection('modContext');
    foreach ($contextCollection as $context) {
        $contexts[] = $context->get('key');
    }
    $contextKeys = array_diff($contexts, array('mgr'));
}

$publishing = isset($scriptProperties['publishing']) ? (boolean) $scriptProperties['publishing'] : true;
if ($publishing) {
    $partitions['auto_publish'] = array('contexts' => $contextKeys);
}

$partitions['resource'] = array('contexts' => $contextKeys);

if (!isset($scriptProperties['elements']) || $scriptProperties['elements']) {
    $partitions['scripts'] = array();
    $paths[] = 'elements/';
}

if (!isset($scriptProperties['lexicons']) || $scriptProperties['lexicons']) {
    $partitions['lexicon_topics'] = array();
    $paths[] = 'lexicon/';
}

if (isset($scriptProperties['paths'])) {
    $paths = array_merge($paths, array_walk(explode(',', $scriptProperties['paths']), 'trim'));
}

$results = array();
$modx->cacheManager->refresh($partitions, $results);

/* invoke OnSiteRefresh event */
$modx->invokeEvent('OnSiteRefresh',array(
    'results' => $results,
));

$o = '';
$num_rows_pub = isset($results['publishing']['published']) ? $results['publishing']['published'] : 0;
$num_rows_unpub = isset($results['publishing']['unpublished']) ? $results['publishing']['unpublished'] : 0;

sleep(1);
$modx->log(modX::LOG_LEVEL_INFO,$modx->lexicon('refresh_published',array( 'num' => $num_rows_pub )));
$modx->log(modX::LOG_LEVEL_INFO,$modx->lexicon('refresh_unpublished',array( 'num' => $num_rows_unpub )).'<hr />');
$modx->log(modX::LOG_LEVEL_INFO,$modx->lexicon('cache_files_deleted'));
if (count($results['deleted_files_count']) > 0) {
    foreach ($results['deleted_files'] as $file) {
        $modx->log(modX::LOG_LEVEL_INFO,$file);
    }
}

sleep(1);
$modx->log(modX::LOG_LEVEL_INFO,'COMPLETED');

return $modx->error->success($o);