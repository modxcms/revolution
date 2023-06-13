<?php

namespace MODX\Revolution;

use PDO;
use xPDO\Om\xPDOObject;
use xPDO\xPDO;

/**
 * Defines an interface to provide configurable access policies for principals.
 *
 * @property modX|xPDO $xpdo
 *
 * @package MODX\Revolution
 */
class modAccessibleObject extends xPDOObject
{
    /**
     * A local cache of access policies for the instance.
     *
     * @var array
     */
    protected $_policies = [];

    /**
     * Custom instance from row loader that respects policy checking
     *
     * @param xPDO|modX $xpdo      A reference to the xPDO/modX object.
     * @param string    $className The name of the class by which to grab the instance from
     * @param mixed     $criteria  A criteria to use when grabbing this instance
     * @param array     $row       The row to select
     *
     * @return modAccessibleObject|null An instance of the object
     */
    public static function _loadInstance(& $xpdo, $className, $criteria, $row)
    {
        /** @var modAccessibleObject $instance */
        $instance = xPDOObject:: _loadInstance($xpdo, $className, $criteria, $row);
        if ($instance instanceof modAccessibleObject && !$instance->checkPolicy('load')) {
            if ($xpdo instanceof modX) {
                $userid = $xpdo->getLoginUserID();
                if (!$userid) {
                    $userid = '0';
                }
                $xpdo->log(xPDO::LOG_LEVEL_INFO,
                    "Principal {$userid} does not have permission to load object of class {$instance->_class} with primary key: " . (is_object($instance) && method_exists($instance,
                        'getPrimaryKey') ? print_r($instance->getPrimaryKey(), true) : ''));
            }
            $instance = null;
        }

        return $instance;
    }

    /**
     * Custom instance loader for collections that respects policy checking.
     *
     * {@inheritdoc}
     */
    public static function _loadCollectionInstance(
        xPDO & $xpdo,
        array & $objCollection,
        $className,
        $criteria,
        $row,
        $fromCache,
        $cacheFlag = true
    ) {
        $loaded = false;
        if ($obj = modAccessibleObject:: _loadInstance($xpdo, $className, $criteria, $row)) {
            if (($cacheKey = $obj->getPrimaryKey()) && !$obj->isLazy()) {
                if (is_array($cacheKey)) {
                    $pkval = implode('-', $cacheKey);
                } else {
                    $pkval = $cacheKey;
                }
                if ($xpdo->getOption(xPDO::OPT_CACHE_DB_COLLECTIONS, [],
                        1) == 2 && $xpdo->_cacheEnabled && $cacheFlag) {
                    if (!$fromCache) {
                        $pkCriteria = $xpdo->newQuery($className, $cacheKey, $cacheFlag);
                        $xpdo->toCache($pkCriteria, $obj, $cacheFlag);
                    } else {
                        $obj->_cacheFlag = true;
                    }
                }
                $objCollection[$pkval] = $obj;
                $loaded = true;
            } else {
                $objCollection[] = $obj;
                $loaded = true;
            }
        }

        return $loaded;
    }

    /**
     * Custom instance loader that forces access policy checking.
     *
     * {@inheritdoc}
     */
    public static function load(xPDO & $xpdo, $className, $criteria, $cacheFlag = true)
    {
        $instance = null;
        $fromCache = false;
        if ($className = $xpdo->loadClass($className)) {
            if (!is_object($criteria)) {
                $criteria = $xpdo->getCriteria($className, $criteria, $cacheFlag);
            }
            if (is_object($criteria)) {
                $row = null;
                if ($xpdo->_cacheEnabled && $criteria->cacheFlag && $cacheFlag) {
                    $row = $xpdo->fromCache($criteria, $className);
                }
                if ($row === null || !is_array($row)) {
                    if ($rows = xPDOObject:: _loadRows($xpdo, $className, $criteria)) {
                        $row = $rows->fetch(PDO::FETCH_ASSOC);
                        $rows->closeCursor();
                    }
                } else {
                    $fromCache = true;
                }
                if (!is_array($row)) {
                    if ($xpdo->getDebug() === true) {
                        $xpdo->log(xPDO::LOG_LEVEL_DEBUG,
                            "Fetched empty result set from statement: " . print_r($criteria->sql,
                                true) . " with bindings: " . print_r($criteria->bindings, true));
                    }
                } else {
                    $instance = modAccessibleObject:: _loadInstance($xpdo, $className, $criteria, $row);
                    if (is_object($instance)) {
                        if (!$fromCache && $cacheFlag && $xpdo->_cacheEnabled) {
                            $xpdo->toCache($criteria, $instance, $cacheFlag);
                            if ($xpdo->getOption(xPDO::OPT_CACHE_DB_OBJECTS_BY_PK) && ($cacheKey = $instance->getPrimaryKey()) && !$instance->isLazy()) {
                                $pkCriteria = $xpdo->newQuery($className, $cacheKey, $cacheFlag);
                                $xpdo->toCache($pkCriteria, $instance, $cacheFlag);
                            }
                        }
                        if ($xpdo->getDebug() === true) {
                            $xpdo->log(xPDO::LOG_LEVEL_DEBUG,
                                "Loaded object instance: " . print_r($instance->toArray('', true), true));
                        }
                    }
                }
            } else {
                $xpdo->log(xPDO::LOG_LEVEL_ERROR,
                    'No valid statement could be found in or generated from the given criteria.');
            }
        } else {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Invalid class specified: ' . $className);
        }

        return $instance;
    }

    /**
     * Custom collection loader that forces access policy checking.
     *
     * {@inheritdoc}
     */
    public static function loadCollection(xPDO & $xpdo, $className, $criteria = null, $cacheFlag = true)
    {
        $objCollection = [];
        if (!$className = $xpdo->loadClass($className)) {
            return $objCollection;
        }
        $rows = false;
        $fromCache = false;
        $collectionCaching = (integer)$xpdo->getOption(xPDO::OPT_CACHE_DB_COLLECTIONS, [], 1);
        if (!is_object($criteria)) {
            $criteria = $xpdo->getCriteria($className, $criteria, $cacheFlag);
        }
        if ($collectionCaching > 0 && $xpdo->_cacheEnabled && $cacheFlag) {
            $rows = $xpdo->fromCache($criteria);
            $fromCache = (is_array($rows) && !empty($rows));
        }
        if (!$fromCache && is_object($criteria)) {
            $rows = xPDOObject:: _loadRows($xpdo, $className, $criteria);
        }
        if (is_array($rows)) {
            foreach ($rows as $row) {
                modAccessibleObject:: _loadCollectionInstance($xpdo, $objCollection, $className, $criteria, $row,
                    $fromCache, $cacheFlag);
            }
        } elseif (is_object($rows)) {
            $cacheRows = [];
            while ($row = $rows->fetch(PDO::FETCH_ASSOC)) {
                modAccessibleObject:: _loadCollectionInstance($xpdo, $objCollection, $className, $criteria, $row,
                    $fromCache, $cacheFlag);
                if ($collectionCaching > 0 && $xpdo->_cacheEnabled && $cacheFlag && !$fromCache) {
                    $cacheRows[] = $row;
                }
            }
            if ($collectionCaching > 0 && $xpdo->_cacheEnabled && $cacheFlag && !$fromCache) {
                $rows =& $cacheRows;
            }
        }
        if (!$fromCache && $xpdo->_cacheEnabled && $collectionCaching > 0 && $cacheFlag && !empty($rows)) {
            $xpdo->toCache($criteria, $rows, $cacheFlag);
        }

        return $objCollection;
    }

    /**
     * Custom save that respects access policies.
     *
     * {@inheritdoc}
     */
    public function save($cacheFlag = null)
    {
        if (!$this->checkPolicy('save')) {
            $this->xpdo->error->failure($this->xpdo->lexicon('permission_denied'));
        }
        $saved = parent:: save($cacheFlag);

        return $saved;
    }

    /**
     * Custom remove that respects access policies.
     *
     * {@inheritdoc}
     */
    public function remove(array $ancestors = [])
    {
        if (!$this->checkPolicy('remove')) {
            $this->xpdo->error->failure($this->xpdo->lexicon('permission_denied'));
        }
        $removed = parent:: remove($ancestors);

        return $removed;
    }

    /**
     * Determine if the current/specified user attributes satisfy the object policy.
     *
     * @param array|string $criteria An associative array providing a key and value to
     *                               search for within the matched policy attributes between policy and
     *                               principal, or the name of a permission to check.
     * @param string|array $targets  A target modAccess class name or an array of
     *                               class names to limit the check. In most cases, this does not need to be
     *                               set; derivatives should typically determine what targets to include in
     *                               the findPolicy() implementation.
     * @param modUser      $user
     *
     * @return boolean Returns true if the policy is satisfied or no policy
     * exists.
     */
    public function checkPolicy($criteria, $targets = null, modUser $user = null)
    {
        if ($criteria && $this->xpdo instanceof modX && $this->xpdo->getSessionState() == modX::SESSION_STATE_INITIALIZED) {
            if (!$user) {
                $user = $this->xpdo->user;
            }
            if ($user->get('sudo')) {
                return true;
            }
            if (!is_array($criteria) && is_scalar($criteria)) {
                $criteria = ["{$criteria}" => true];
            }
            $policy = $this->findPolicy();
            if (!empty($policy)) {
                $principal = $user->getAttributes($targets);
                if (!empty($principal)) {
                    foreach ($policy as $policyAccess => $access) {
                        foreach ($access as $targetId => $targetPolicy) {
                            foreach ($targetPolicy as $policyIndex => $applicablePolicy) {
                                if ($this->xpdo->getDebug() === true) {
                                    $this->xpdo->log(xPDO::LOG_LEVEL_DEBUG,
                                        'target pk=' . $this->getPrimaryKey() . '; evaluating policy: ' . print_r($applicablePolicy,
                                            1) . ' against principal for user id=' . $user->id . ': ' . print_r($principal[$policyAccess],
                                            1));
                                }
                                $principalPolicyData = [];
                                $principalAuthority = 9999;
                                if (isset($principal[$policyAccess][$targetId]) && is_array($principal[$policyAccess][$targetId])) {
                                    foreach ($principal[$policyAccess][$targetId] as $acl) {
                                        $principalAuthority = intval($acl['authority']);
                                        $principalPolicyData = $acl['policy'];
                                        $principalId = $acl['principal'];
                                        if ($applicablePolicy['principal'] == $principalId) {
                                            if ($principalAuthority <= $applicablePolicy['authority']) {
                                                if (!$applicablePolicy['policy']) {
                                                    return true;
                                                }
                                                if (empty($principalPolicyData)) {
                                                    $principalPolicyData = [];
                                                }
                                                $matches = array_intersect_assoc($principalPolicyData,
                                                    $applicablePolicy['policy']);
                                                if ($matches) {
                                                    if ($this->xpdo->getDebug() === true) {
                                                        $this->xpdo->log(modX::LOG_LEVEL_DEBUG,
                                                            'Evaluating policy matches: ' . print_r($matches, 1));
                                                    }
                                                    $matched = array_diff_assoc($criteria, $matches);
                                                    if (empty($matched)) {
                                                        return true;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                return false;
            }
        }

        return true;
    }

    /**
     * Find access policies applicable to this object in a specific context.
     *
     * @access protected
     *
     * @param string $context A key identifying a specific context to use when
     *                        searching for the applicable policies. If not provided, the current
     *                        context is used.
     *
     * @return array An array of access policies for this object; an empty
     * array is returned if no policies are assigned to the object.
     */
    public function findPolicy($context = '')
    {
        return [];
    }

    /**
     * Return the currently loaded array of policies.
     *
     * @return array
     */
    public function getPolicies()
    {
        return $this->_policies;
    }

    /**
     * Set the current object's policies.
     *
     * @param array $policies
     *
     * @return void
     */
    public function setPolicies(array $policies = [])
    {
        $this->_policies = $policies;
    }
}
