<?php
/**
 * Refreshes the site cache
 *
 * @package modx
 * @subpackage manager.system
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

$num_rows_pub = isset($results['publishing']['published']) ? $results['publishing']['published'] : 0;
$num_rows_unpub = isset($results['publishing']['unpublished']) ? $results['publishing']['unpublished'] : 0;
$modx->smarty->assign('published',$modx->lexicon('refresh_published',array('num' => $num_rows_pub)));
$modx->smarty->assign('unpublished',$modx->lexicon('refresh_unpublished',array('num' => $num_rows_unpub)));

$modx->smarty->assign('results', $results);

$this->checkFormCustomizationRules();
return $modx->smarty->fetch('system/refresh_site.tpl');