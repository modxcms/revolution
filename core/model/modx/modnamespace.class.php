<?php
/**
 * modNamespace
 *
 * @package modx
 */
/**
 * Represents a Component in the MODX framework. Isolates controllers, lexicons and other logic into the virtual
 * containment space defined by the path of the namespace.
 *
 * @property string $name The key of the namespace
 * @property string $path The absolute path of the namespace. May use {core_path}, {base_path} or {assets_path} as
 * placeholders for the path.
 *
 * @package modx
 */
class modNamespace extends xPDOObject {
    public function save($cacheFlag = null) {
        $saved = parent::save();
        if ($saved && !$this->getOption(xPDO::OPT_SETUP)) {
            $this->xpdo->call('modNamespace','clearCache',array(&$this->xpdo));
        }
        return $saved;
    }

    public function remove(array $ancestors = array()) {
        $removed = parent::remove($ancestors);
        if ($removed && !$this->getOption(xPDO::OPT_SETUP)) {
            $this->xpdo->call('modNamespace','clearCache',array(&$this->xpdo));
        }
        return $removed;
    }

    public static function loadCache(modX $modx) {
        if (!$modx->getCacheManager()) {
            return array();
        }
        $cacheKey= 'namespaces';
        $cache = $modx->cacheManager->get($cacheKey, array(
            xPDO::OPT_CACHE_KEY => $modx->getOption('cache_namespaces_key', null, 'namespaces'),
            xPDO::OPT_CACHE_HANDLER => $modx->getOption('cache_namespaces_handler', null,$modx->getOption(xPDO::OPT_CACHE_HANDLER)),
            xPDO::OPT_CACHE_FORMAT => (integer) $modx->getOption('cache_namespaces_format', null, $modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
        ));
        if (empty($cache)) {
            $cache = $modx->cacheManager->generateNamespacesCache($cacheKey);
        }
        return $cache;
    }

    public static function clearCache(modX $modx) {
        $cacheKey= 'namespaces';
        $cleared = $modx->cacheManager->delete($cacheKey, array(
            xPDO::OPT_CACHE_KEY => $modx->getOption('cache_namespaces_key', null, 'namespaces'),
            xPDO::OPT_CACHE_HANDLER => $modx->getOption('cache_namespaces_handler', null,$modx->getOption(xPDO::OPT_CACHE_HANDLER)),
            xPDO::OPT_CACHE_FORMAT => (integer) $modx->getOption('cache_namespaces_format', null, $modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
        ));
        return $cleared;
    }

    public function getCorePath() {
        $path = $this->get('path');
        return $this->xpdo->call('modNamespace','translatePath',array(&$this->xpdo,$path));
    }

    public function getAssetsPath() {
        $path = $this->get('assets_path');
        return $this->xpdo->call('modNamespace','translatePath',array(&$this->xpdo,$path));
    }

    public static function translatePath(xPDO &$xpdo,$path) {
        return str_replace(array(
            '{core_path}',
            '{base_path}',
            '{assets_path}',
        ),array(
            $xpdo->getOption('core_path',null,MODX_CORE_PATH),
            $xpdo->getOption('base_path',null,MODX_BASE_PATH),
            $xpdo->getOption('assets_path',null,MODX_ASSETS_PATH),
        ),$path);
    }
}