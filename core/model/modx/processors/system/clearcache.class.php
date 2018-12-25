<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Refreshes the site cache
 *
 * @package modx
 * @subpackage system
 */
class modSystemClearCacheProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('empty_cache');
    }

    public function process() {
        $this->runBeforeEvents();

        $results = array();
        $partitions = $this->getPartitions();
        $this->modx->cacheManager->refresh($partitions, $results);

        $results['paths'] = $this->clearByPaths();

        /* invoke OnSiteRefresh event */
        $this->modx->invokeEvent('OnSiteRefresh',array(
            'results' => $results,
            'partitions' => $partitions,
        ));

        $o = '';
        sleep(1);
        $result = reset($results);
        $partition = key($results);
        while ($partition && $result) {
            switch ($partition) {
                case 'auto_publish':
                    $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('refresh_auto_publish'));
                    $this->modx->log(modX::LOG_LEVEL_INFO, "-> " . $this->modx->lexicon('refresh_published', array('num' => $result['published'])));
                    $this->modx->log(modX::LOG_LEVEL_INFO, "-> " . $this->modx->lexicon('refresh_unpublished', array('num' => $result['unpublished'])));
                    break;
                case 'context_settings':
                    $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('refresh_context_settings'));
                    foreach ($result as $ctxKey => $ctxResult) {
                        $this->modx->log(modX::LOG_LEVEL_INFO, "-> " . $ctxKey . ": " . $this->modx->lexicon('refresh_' . ($ctxResult ? 'success' : 'failure')));
                    }
                    break;
                case 'paths':
                    $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('cache_files_deleted'));
                    foreach ($result as $path => $pathResults) {
                        $this->modx->log(modX::LOG_LEVEL_INFO, "-> " . $path);
                        foreach ($pathResults as $deleted) {
                            $this->modx->log(modX::LOG_LEVEL_INFO, "--> " . $deleted);
                        }
                    }
                    break;
                default:
                    if (is_bool($result)) {
                        $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('refresh_' . $partition) . ': ' . $this->modx->lexicon('refresh_' . ($result ? 'success' : 'failure')));
                    } elseif (is_array($result)) {
                        $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('refresh_' . $partition) . ': ' . print_r($result, true));
                    }
                    break;
            }
            $result = next($results);
            $partition = key($results);
        }
        $this->modx->log(modX::LOG_LEVEL_INFO, 'COMPLETED');

        $this->runAfterEvents();

        return $this->success($o);
    }

    public function runBeforeEvents() {
        /* invoke OnBeforeCacheUpdate event */
        $this->modx->invokeEvent('OnBeforeCacheUpdate');
    }

    public function runAfterEvents() {
        $this->modx->logManagerAction('clear_cache', '', $this->modx->context->key);
    }

    public function getPartitions() {
        $contextKeys = $this->getProperty('contexts',array());
        $contextKeys = is_string($contextKeys) ? explode(',',$contextKeys) : $contextKeys;
        if (!empty($contextKeys)) {
            array_walk($contextKeys, 'trim');
        } else {
            $query = $this->modx->newQuery('modContext');
            $query->select($this->modx->escape('key'));
            if ($query->prepare() && $query->stmt->execute()) {
                $contextKeys = $query->stmt->fetchAll(PDO::FETCH_COLUMN);
            }
        }

        $partitions = array();

        if ($this->getProperty('publishing', true)) {
            $partitions['auto_publish'] = array('contexts' => array_diff($contextKeys, array('mgr')));
        }

        $partitions['system_settings'] = array();
        $partitions['context_settings'] = array('contexts' => $contextKeys);

        if ($this->modx->getOption('cache_db', null, false)) {
            $partitions['db'] = array();
        }

        if ($this->getProperty('media_sources', false)) {
            $partitions['media_sources'] = array();
        }

        if ($this->getProperty('lexicons', true)) {
            $partitions['lexicon_topics'] = array();
        }

        if ($this->getProperty('elements', true)) {
            $partitions['scripts'] = array();
        }

        $partitions['default'] = array();

        $partitions['resource'] = array('contexts' => array_diff($contextKeys, array('mgr')));

        if ($this->getProperty('menu', false)) {
            $partitions['menu'] = array();
        };

        if ($this->getProperty('action_map', false)) {
            $partitions['action_map'] = array();
        }

        return $partitions;
    }

    public function clearByPaths() {
        $pathResults = array();
        $paths = $this->getProperty('paths',false);
        if (!empty($paths)) {
            /* deprecated: use a dedicated cache partition rather than specifying paths */
            $this->modx->deprecated('2.1.4', 'Use a dedicated cache partition rather than specifying paths.', 'modSystemClearCacheProcessor clearByPaths');
            $paths = array_walk(explode(',',$paths), 'trim');
            if (!empty($paths)) {
                foreach ($paths as $path) {
                    $fullPath = $this->modx->cacheManager->getCachePath() . $path;
                    if (is_dir($fullPath)) {
                        $pathResults[$path] = $this->modx->cacheManager->deleteTree($fullPath,array(
                            'deleteTop' => false,
                            'skipDirs' => false,
                            'extensions' => array('.cache.php','.tpl.php')
                        ));
                    } elseif (is_file($fullPath)) {
                        $pathResults[$path] = @unlink($fullPath);
                    } else {
                        $pathResults[$path] = false;
                    }
                }
            }
        }
        return $pathResults;
    }
}
return 'modSystemClearCacheProcessor';
