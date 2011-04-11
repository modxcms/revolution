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
    $query = $modx->newQuery('modContext');
    $query->select($modx->escape('key'));
    if ($query->prepare() && $query->stmt->execute()) {
        $contextKeys = $query->stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}

$publishing = isset($scriptProperties['publishing']) ? (boolean) $scriptProperties['publishing'] : true;
if ($publishing) {
    $partitions['auto_publish'] = array('contexts' => array_diff($contextKeys, array('mgr')));
}

$partitions['system_settings'] = array();
$partitions['context_settings'] = array('contexts' => $contextKeys);
if (!isset($scriptProperties['elements']) || $scriptProperties['elements']) {
    $partitions['scripts'] = array();
}

$partitions['resource'] = array('contexts' => array_diff($contextKeys, array('mgr')));

if (!isset($scriptProperties['lexicons']) || $scriptProperties['lexicons']) {
    $partitions['lexicon_topics'] = array();
}

$results = array();
$modx->cacheManager->refresh($partitions, $results);

/* deprecated — use a dedicated cache partition rather than specifying paths */
if (isset($scriptProperties['paths'])) {
    $paths = array_walk(explode(',', $scriptProperties['paths']), 'trim');
    if (!empty($paths)) {
        foreach ($paths as $path) {
            $fullPath = $modx->cacheManager->getCachePath() . $path;
            if (is_dir($fullPath)) {
                $pathResults[$path] = $modx->cacheManager->deleteTree($fullPath);
            } elseif (is_file($fullPath)) {
                $pathResults[$path] = @unlink($fullPath);
            } else {
                $pathResults[$path] = false;
            }
        }
        $results['paths'] = $pathResults;
    }
}

/* invoke OnSiteRefresh event */
$modx->invokeEvent('OnSiteRefresh',array(
    'results' => $results,
    'partitions' => $partitions
));

$o = '';
sleep(1);
$result = reset($results);
$partition = key($results);
while ($partition && $result) {
    switch ($partition) {
        case 'auto_publish':
            $modx->log(modX::LOG_LEVEL_INFO, $modx->lexicon('refresh_auto_publish'));
            $modx->log(modX::LOG_LEVEL_INFO, "-> " . $modx->lexicon('refresh_published', array('num' => $result['published'])));
            $modx->log(modX::LOG_LEVEL_INFO, "-> " . $modx->lexicon('refresh_unpublished', array('num' => $result['unpublished'])));
            break;
        case 'context_settings':
            $modx->log(modX::LOG_LEVEL_INFO, $modx->lexicon('refresh_context_settings'));
            foreach ($result as $ctxKey => $ctxResult) {
                $modx->log(modX::LOG_LEVEL_INFO, "-> " . $ctxKey . ": " . $modx->lexicon('refresh_' . ($ctxResult ? 'success' : 'failure')));
            }
            break;
        case 'paths':
            $modx->log(modX::LOG_LEVEL_INFO, $modx->lexicon('cache_files_deleted'));
            foreach ($result as $path => $pathResults) {
                $modx->log(modX::LOG_LEVEL_INFO, "-> " . $path);
                foreach ($pathResults as $deleted) {
                    $modx->log(modX::LOG_LEVEL_INFO, "--> " . $deleted);
                }
            }
            break;
        default:
            if (is_bool($result)) {
                $modx->log(modX::LOG_LEVEL_INFO, $modx->lexicon('refresh_' . $partition) . ': ' . $modx->lexicon('refresh_' . ($result ? 'success' : 'failure')));
            } elseif (is_array($result)) {
                $modx->log(modX::LOG_LEVEL_INFO, $modx->lexicon('refresh_' . $partition) . ': ' . print_r($result, true));
            }
            break;
    }
    $result = next($results);
    $partition = key($results);
}

return $modx->error->success($o);