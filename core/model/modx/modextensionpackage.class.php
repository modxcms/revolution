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
 * @package modx
 * @subpackage mysql
 */
class modExtensionPackage extends xPDOSimpleObject {
    public function save($cacheFlag= null) {
        if (!$this->getOption(xPDO::OPT_SETUP)) {
            $isNew = $this->isNew();
            if ($isNew) {
                $this->set('created_at',strftime('%Y-%m-%d %H:%M:%S'));
            } else {
                $this->set('updated_at',strftime('%Y-%m-%d %H:%M:%S'));
            }
        }
        $saved = parent::save($cacheFlag);
        if ($saved && !$this->getOption(xPDO::OPT_SETUP)) {
            $this->xpdo->call('modExtensionPackage','clearCache',array(&$this->xpdo));
        }
        return $saved;
    }

    public function remove(array $ancestors = array()) {
        $removed = parent::remove($ancestors);
        if ($removed && !$this->getOption(xPDO::OPT_SETUP)) {
            $this->xpdo->call('modExtensionPackage','clearCache',array(&$this->xpdo));
        }
        return $removed;
    }


    /**
     * @static
     * @param xPDO|modX $modx
     * @return array|mixed
     */
    public static function loadCache(xPDO &$modx) {
        if (!$modx->getCacheManager()) {
            return array();
        }
        $cacheKey= 'extension-packages';
        $cache = $modx->cacheManager->get($cacheKey, array(
            xPDO::OPT_CACHE_KEY => $modx->getOption('cache_extension_packages_key', null, 'namespaces'),
            xPDO::OPT_CACHE_HANDLER => $modx->getOption('cache_extension_packages_handler', null,$modx->getOption(xPDO::OPT_CACHE_HANDLER)),
            xPDO::OPT_CACHE_FORMAT => (integer) $modx->getOption('cache_extension_packages_format', null, $modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
        ));
        if (empty($cache)) {
            $cache = $modx->cacheManager->generateExtensionPackagesCache($cacheKey);
        }
        return $cache;
    }

    public static function clearCache(modX $modx) {
        $cacheKey= 'extension-packages';
        $cleared = $modx->cacheManager->delete($cacheKey, array(
            xPDO::OPT_CACHE_KEY => $modx->getOption('cache_extension_packages_key', null, 'namespaces'),
            xPDO::OPT_CACHE_HANDLER => $modx->getOption('cache_extension_packages_handler', null,$modx->getOption(xPDO::OPT_CACHE_HANDLER)),
            xPDO::OPT_CACHE_FORMAT => (integer) $modx->getOption('cache_extension_packages_format', null, $modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
        ));
        return $cleared;
    }
}
