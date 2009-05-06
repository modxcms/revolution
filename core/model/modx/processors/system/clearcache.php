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
    'extensions' => array('.cache.php', '.msg.php')
);
if ($modx->getOption('cache_db')) $options['objects'] = '*';

$results= $modx->cacheManager->clearCache($paths, $options);

/* invoke OnSiteRefresh event */
$modx->invokeEvent('OnSiteRefresh');

$o = '';
$num_rows_pub = isset($results['publishing']['published']) ? $results['publishing']['published'] : 0;
$num_rows_unpub = isset($results['publishing']['unpublished']) ? $results['publishing']['unpublished'] : 0;
$o .= sprintf($modx->lexicon('refresh_published'), $num_rows_pub).'<br />';
$o .= sprintf($modx->lexicon('refresh_unpublished'), $num_rows_unpub).'<hr />';
$o .= $modx->lexicon('cache_files_deleted');
if (count($results['deleted_files_count']) > 0) {
    $o .= '<ul>';
    foreach ($results['deleted_files'] as $file) {
        $o .= '<li>'.$file.'</li>';
    }
    $o .= '</ul>';
}

return $modx->error->success($o);