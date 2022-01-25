<?php

namespace MODX\Revolution;

use xPDO\Cache\xPDOCacheManager;
use xPDO\Om\xPDOSimpleObject;
use xPDO\xPDO;

/**
 * Class modExtensionPackage
 *
 * @property string $namespace
 * @property string $name
 * @property string $path
 * @property string $table_prefix
 * @property string $service_class
 * @property string $service_name
 * @property string $created_at
 * @property string $updated_at
 *
 * @package MODX\Revolution
 */
class modExtensionPackage extends xPDOSimpleObject
{
    public function save($cacheFlag = null)
    {
        if (!$this->getOption(xPDO::OPT_SETUP)) {
            $isNew = $this->isNew();
            if ($isNew) {
                $this->set('created_at', date('Y-m-d H:i:s'));
            } else {
                $this->set('updated_at', date('Y-m-d H:i:s'));
            }
        }
        $saved = parent::save($cacheFlag);
        if ($saved && !$this->getOption(xPDO::OPT_SETUP)) {
            $this->xpdo->call(modExtensionPackage::class, 'clearCache', [&$this->xpdo]);
        }

        return $saved;
    }

    public function remove(array $ancestors = [])
    {
        $removed = parent::remove($ancestors);
        if ($removed && !$this->getOption(xPDO::OPT_SETUP)) {
            $this->xpdo->call(modExtensionPackage::class, 'clearCache', [&$this->xpdo]);
        }

        return $removed;
    }


    /**
     * @static
     *
     * @param xPDO|modX $modx
     *
     * @return array|mixed
     */
    public static function loadCache(xPDO &$modx)
    {
        if (!$modx->getCacheManager()) {
            return [];
        }
        $cacheKey = 'extension-packages';
        $cache = $modx->cacheManager->get($cacheKey, [
            xPDO::OPT_CACHE_KEY => $modx->getOption('cache_extension_packages_key', null, 'namespaces'),
            xPDO::OPT_CACHE_HANDLER => $modx->getOption('cache_extension_packages_handler', null,
                $modx->getOption(xPDO::OPT_CACHE_HANDLER)),
            xPDO::OPT_CACHE_FORMAT => (integer)$modx->getOption('cache_extension_packages_format', null,
                $modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
        ]);
        if (empty($cache)) {
            $cache = $modx->cacheManager->generateExtensionPackagesCache($cacheKey);
        }

        return $cache;
    }

    public static function clearCache(modX $modx)
    {
        $cacheKey = 'extension-packages';
        $cleared = $modx->cacheManager->delete($cacheKey, [
            xPDO::OPT_CACHE_KEY => $modx->getOption('cache_extension_packages_key', null, 'namespaces'),
            xPDO::OPT_CACHE_HANDLER => $modx->getOption('cache_extension_packages_handler', null,
                $modx->getOption(xPDO::OPT_CACHE_HANDLER)),
            xPDO::OPT_CACHE_FORMAT => (integer)$modx->getOption('cache_extension_packages_format', null,
                $modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
        ]);

        return $cleared;
    }
}
