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

$paths = array(
    'config.cache.php',
    'sitePublishing.idx.php',
    'registry/mgr/workspace/',
    'lexicon/',
);
$contexts = $modx->getCollection('modContext');
foreach ($contexts as $context) {
    $paths[] = $context->get('key') . '/';
}

$options = array(
    'publishing' => 1,
    'extensions' => array('.cache.php', '.msg.php', '.tpl.php'),
);
if ($modx->getOption('cache_db')) $options['objects'] = '*';

$results= $modx->cacheManager->clearCache($paths, $options);

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