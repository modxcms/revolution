<?php

namespace MODX\Revolution;

use PDO;
use xPDO\Cache\xPDOCacheManager;
use xPDO\Om\xPDOCriteria;
use xPDO\xPDO;

/**
 * Represents a Component in the MODX framework. Isolates controllers, lexicons and other logic into the virtual
 * containment space defined by the path of the namespace.
 *
 * @property string                $name The key of the namespace
 * @property string                $path The absolute path of the namespace. May use {core_path}, {base_path} or {assets_path} as
 * placeholders for the path.
 * @property string                $assets_path
 *
 * @property modLexiconEntry[]     $LexiconEntries
 * @property modSystemSetting[]    $SystemSettings
 * @property modContextSetting[]   $ContextSettings
 * @property modUserSetting[]      $UserSettings
 * @property modExtensionPackage[] $ExtensionPackages
 * @property modAccessNamespace[]  $Acls
 *
 * @package MODX\Revolution
 */
class modNamespace extends modAccessibleObject
{
    public function save($cacheFlag = null)
    {
        $saved = parent::save();
        if ($saved && !$this->getOption(xPDO::OPT_SETUP)) {
            $this->xpdo->call(modNamespace::class, 'clearCache', [&$this->xpdo]);
        }

        return $saved;
    }

    public function remove(array $ancestors = [])
    {
        $removed = parent::remove($ancestors);
        if ($removed && !$this->getOption(xPDO::OPT_SETUP)) {
            $this->xpdo->call(modNamespace::class, 'clearCache', [&$this->xpdo]);
        }

        return $removed;
    }

    public static function loadCache(modX $modx)
    {
        if (!$modx->getCacheManager()) {
            return [];
        }
        $cacheKey = 'namespaces';
        $cache = $modx->cacheManager->get($cacheKey, [
            xPDO::OPT_CACHE_KEY => $modx->getOption('cache_namespaces_key', null, 'namespaces'),
            xPDO::OPT_CACHE_HANDLER => $modx->getOption('cache_namespaces_handler', null,
                $modx->getOption(xPDO::OPT_CACHE_HANDLER)),
            xPDO::OPT_CACHE_FORMAT => (integer)$modx->getOption('cache_namespaces_format', null,
                $modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
        ]);
        if (empty($cache)) {
            $cache = $modx->cacheManager->generateNamespacesCache($cacheKey);
        }

        return $cache;
    }

    public static function clearCache(modX $modx)
    {
        $cacheKey = 'namespaces';
        $cleared = $modx->cacheManager->delete($cacheKey, [
            xPDO::OPT_CACHE_KEY => $modx->getOption('cache_namespaces_key', null, 'namespaces'),
            xPDO::OPT_CACHE_HANDLER => $modx->getOption('cache_namespaces_handler', null,
                $modx->getOption(xPDO::OPT_CACHE_HANDLER)),
            xPDO::OPT_CACHE_FORMAT => (integer)$modx->getOption('cache_namespaces_format', null,
                $modx->getOption(xPDO::OPT_CACHE_FORMAT, null, xPDOCacheManager::CACHE_PHP)),
        ]);

        return $cleared;
    }

    public function getCorePath()
    {
        $path = $this->get('path');

        return $this->xpdo->call(modNamespace::class, 'translatePath', [&$this->xpdo, $path]);
    }

    public function getAssetsPath()
    {
        $path = $this->get('assets_path');

        return $this->xpdo->call(modNamespace::class, 'translatePath', [&$this->xpdo, $path]);
    }

    public static function translatePath(xPDO &$xpdo, $path)
    {
        return str_replace([
            '{core_path}',
            '{base_path}',
            '{assets_path}',
        ], [
            $xpdo->getOption('core_path', null, MODX_CORE_PATH),
            $xpdo->getOption('base_path', null, MODX_BASE_PATH),
            $xpdo->getOption('assets_path', null, MODX_ASSETS_PATH),
        ], $path);
    }

    public function checkPolicy($criteria, $targets = null, modUser $user = null)
    {
        return parent::checkPolicy($criteria, $targets, $user);
    }

    /**
     * Find all policies for this object
     *
     * @param string $context
     *
     * @return array
     */
    public function findPolicy($context = '')
    {
        $policy = [];
        $context = 'mgr';

        if (empty($this->_policies) || !isset($this->_policies[$context])) {
            $accessTable = $this->xpdo->getTableName(modAccessNamespace::class);
            $namespaceTable = $this->xpdo->getTableName(modNamespace::class);
            $policyTable = $this->xpdo->getTableName(modAccessPolicy::class);
            $sql = "SELECT Acl.target, Acl.principal, Acl.authority, Acl.policy, Policy.data FROM {$accessTable} Acl " .
                "LEFT JOIN {$policyTable} Policy ON Policy.id = Acl.policy " .
                "JOIN {$namespaceTable} Namespace ON Acl.principal_class = {$this->xpdo->quote(modUserGroup::class)} " .
                "AND (Acl.context_key = :context OR Acl.context_key IS NULL OR Acl.context_key = '') " .
                "AND Namespace.name = Acl.target " .
                "WHERE Acl.target = :namespace " .
                "GROUP BY Acl.target, Acl.principal, Acl.authority, Acl.policy";
            $bindings = [
                ':namespace' => $this->get('name'),
                ':context' => $context,
            ];
            $query = new xPDOCriteria($this->xpdo, $sql, $bindings);
            if ($query->stmt && $query->stmt->execute()) {
                while ($row = $query->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $policy[modAccessNamespace::class][$row['target']][] = [
                        'principal' => $row['principal'],
                        'authority' => $row['authority'],
                        'policy' => $row['data'] ? $this->xpdo->fromJSON($row['data'], true) : [],
                    ];
                }
            }
            $this->_policies[$context] = $policy;
        } else {
            $policy = $this->_policies[$context];
        }

        return $policy;
    }
}
