<?php
/**
 * Defines an interface to provide configurable access policies for principals.
 *
 * @package modx
 */
class modAccessibleObject extends xPDOObject {
    /**
     * A local cache of access policies for the instance.
     * @var array
     */
    protected $_policies = array();

    /**
     * Custom instance loader for collections that respects policy checking.
     *
     * {@inheritdoc}
     */
    protected static function _loadCollectionInstance(xPDO & $xpdo, array & $objCollection, $className, $criteria, $row, $fromCache, $cacheFlag=true) {
        $loaded = false;
        if ($obj= xPDOObject :: _loadInstance($xpdo, $className, $criteria, $row)) {
            if (($cacheKey= $obj->getPrimaryKey()) && !$obj->isLazy()) {
                if (is_array($cacheKey)) {
                    $pkval= implode('-', $cacheKey);
                } else {
                    $pkval= $cacheKey;
                }
                if ($obj->checkPolicy('load')) {
                    if ($xpdo->getOption(xPDO::OPT_CACHE_DB_COLLECTIONS, array(), 1) == 2 && $xpdo->_cacheEnabled && $cacheFlag) {
                        if (!$fromCache) {
                            $pkCriteria = $xpdo->newQuery($className, $cacheKey, $cacheFlag);
                            $xpdo->toCache($criteria, $obj, $cacheFlag);
                        } else {
                            $obj->_cacheFlag= true;
                        }
                    }
                    $objCollection[$pkval]= $obj;
                    $loaded = true;
                }
            } else {
                if ($obj->checkPolicy('load')) {
                    $objCollection[]= $obj;
                    $loaded = true;
                }
            }
        }
        return $loaded;
    }

    /**
     * Custom instance loader that forces access policy checking.
     *
     * {@inheritdoc}
     */
    public static function load(xPDO & $xpdo, $className, $criteria, $cacheFlag= true) {
        $object = null;
        $object = xPDOObject :: load($xpdo, $className, $criteria, $cacheFlag);
        if ($object && !$object->checkPolicy('load')) {
            $userid = $xpdo->getLoginUserID();
            if (!$userid) {
                $userid = '0';
            }
            $xpdo->log(xPDO::LOG_LEVEL_INFO, "Principal {$userid} does not have access to requested object of class {$object->_class} with primary key " . print_r($object->getPrimaryKey(false), true));
            $object = null;
        }
        return $object;
    }

    /**
     * Custom collection loader that forces access policy checking.
     *
     * {@inheritdoc}
     */
    public static function loadCollection(xPDO & $xpdo, $className, $criteria= null, $cacheFlag= true) {
        $objCollection= array ();
        if (!$className= $xpdo->loadClass($className)) return $objCollection;
        $rows= false;
        $fromCache= false;
        $collectionCaching = (integer) $xpdo->getOption(xPDO::OPT_CACHE_DB_COLLECTIONS, array(), 1);
        if (!is_object($criteria)) {
            $criteria= $xpdo->getCriteria($className, $criteria, $cacheFlag);
        }
        if (is_object($criteria)) {
            if ($collectionCaching > 0 && $xpdo->_cacheEnabled && $cacheFlag) {
                $rows= $xpdo->fromCache($criteria, $className);
                $fromCache = (is_array($rows) && !empty($rows));
            }
            if (!$fromCache) {
                $rows= xPDOObject :: _loadRows($xpdo, $className, $criteria);
            }
            $cacheRows = array();
            if (is_array ($rows)) {
                foreach ($rows as $row) {
                    if (modAccessibleObject :: _loadCollectionInstance($xpdo, $objCollection, $className, $criteria, $row, $fromCache, $cacheFlag)) {
                        if ($collectionCaching > 0 && $xpdo->_cacheEnabled && $cacheFlag && !$fromCache) $cacheRows[] = $row;
                    }
                }
            } elseif (is_object($rows)) {
                while ($row = $rows->fetch(PDO::FETCH_ASSOC)) {
                    if (modAccessibleObject :: _loadCollectionInstance($xpdo, $objCollection, $className, $criteria, $row, $fromCache, $cacheFlag)) {
                        if ($collectionCaching > 0 && $xpdo->_cacheEnabled && $cacheFlag && !$fromCache) $cacheRows[] = $row;
                    }
                }
            }
            if (!$fromCache && $xpdo->_cacheEnabled && $collectionCaching > 0 && $cacheFlag && !empty($cacheRows)) {
                $xpdo->toCache($criteria, $cacheRows, $cacheFlag);
            }
        } else {
            $xpdo->log(xPDO::LOG_LEVEL_ERROR, 'modAccessibleObject::loadCollection() - No valid statement could be found in or generated from the given criteria.');
        }
        return $objCollection;
    }

    /**
     * Custom save that respects access policies.
     *
     * {@inheritdoc}
     */
    public function save($cacheFlag = null) {
        $saved = false;
        if (!$this->checkPolicy('save')) {
            $this->xpdo->error->failure($this->xpdo->lexicon('permission_denied'));
        }
        $saved = parent :: save($cacheFlag);
        return $saved;
    }

    /**
     * Custom remove that respects access policies.
     *
     * {@inheritdoc}
     */
    public function remove(array $ancestors= array ()) {
        $removed = false;
        if (!$this->checkPolicy('remove')) {
            $this->xpdo->error->failure($this->xpdo->lexicon('permission_denied'));
        }
        $removed = parent :: remove($ancestors);
        return $removed;
    }

    /**
     * Determine if the current user attributes satisfy the object policy.
     *
     * @param array $criteria An associative array providing a key and value to
     * search for within the matched policy attributes between policy and
     * principal.
     * @param string|array $targets A target modAccess class name or an array of
     * class names to limit the check. In most cases, this does not need to be
     * set; derivatives should typically determine what targets to include in
     * the findPolicy() implementation.
     * @return boolean Returns true if the policy is satisfied or no policy
     * exists.
     */
    public function checkPolicy($criteria, $targets = null) {
        if ($criteria && $this->xpdo instanceof modX && $this->xpdo->getSessionState() == modX::SESSION_STATE_INITIALIZED) {
            if (!is_array($criteria) && is_scalar($criteria)) {
                $criteria = array("{$criteria}" => true);
            }
            $policy = $this->findPolicy();
            if (!empty($policy)) {
                $principal = $this->xpdo->user->getAttributes($targets);
                if (!empty($principal)) {
                    foreach ($policy as $policyAccess => $access) {
                        foreach ($access as $targetId => $targetPolicy) {
                            foreach ($targetPolicy as $policyIndex => $applicablePolicy) {
                                if ($this->xpdo->getDebug() === true)
                                    $this->xpdo->log(xPDO::LOG_LEVEL_DEBUG, 'target pk='. $this->getPrimaryKey() .'; evaluating policy: ' . print_r($applicablePolicy, 1) . ' against principal for user id=' . $this->xpdo->getLoginUserID() .': ' . print_r($principal[$policyAccess], 1));
                                $principalPolicyData = array();
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
                                                $matches = array_intersect_assoc($principalPolicyData, $applicablePolicy['policy']);
                                                if ($matches) {
                                                    if ($this->xpdo->getDebug() === true)
                                                        $this->xpdo->log(modX::LOG_LEVEL_DEBUG, 'Evaluating policy matches: ' . print_r($matches, 1));
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
     * @param string $context A key identifying a specific context to use when
     * searching for the applicable policies. If not provided, the current
     * context is used.
     * @return array An array of access policies for this object; an empty
     * array is returned if no policies are assigned to the object.
     */
    public function findPolicy($context = '') {
        return array();
    }

    public function getPolicies() {
        return $this->_policies;
    }

    public function setPolicies(array $policies = array()) {
        $this->_policies = $policies;
    }
}