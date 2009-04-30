<?php
/**
 * Defines an interface to provide configurable access policies for principals.
 *
 * @package modx
 * @subpackage mysql
 */
class modAccessibleObject extends xPDOObject {
    /**
     * A local cache of access policies for the instance.
     * @var array
     */
    var $_policies = array();

    function modAccessibleObject(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }

    /**
     * Custom instance loader for collections that respects policy checking.
     * 
     * {@inheritdoc}
     */
    function _loadCollectionInstance(& $xpdo, & $objCollection, $className, $criteria, $row, $fromCache, $cacheFlag) {
        if ($obj= xPDOObject :: _loadInstance($xpdo, $className, $criteria, $row)) {
            if (($pkval= $obj->getPrimaryKey()) && !$obj->isLazy()) {
                if (is_array($pkval)) {
                    $cacheKey= implode('_', $pkval);
                    $pkval= implode('-', $pkval);
                } else {
                    $cacheKey= $pkval;
                }
                if ($obj->checkPolicy('load')) {
                    if ($xpdo->_cacheEnabled && $cacheFlag) {
                        if (!$fromCache) {
                            $xpdo->toCache($obj->_class . '_' . $cacheKey, $obj, $cacheFlag);
                        } else {
                            $obj->_cacheFlag= true;
                        }
                    }
                    $objCollection[$pkval]= $obj;
                }
            } else {
                if ($obj->checkPolicy('load')) {
                    $objCollection[]= $obj;
                }
            }
        }
    }

    /**
     * Custom instance loader that forces access policy checking.
     *
     * {@inheritdoc}
     */
    function load(& $xpdo, $className, $criteria, $cacheFlag= true) {
        $object = null;
        $object = xPDOObject :: load($xpdo, $className, $criteria, $cacheFlag);
        if ($object && !$object->checkPolicy('load')) {
            $userid = $xpdo->getLoginUserID();
            if (!$userid) {
                $userid = '0';
            }
            $xpdo->log(XPDO_LOG_LEVEL_INFO, "Principal {$userid} does not have access to requested object of class {$object->_class} with primary key " . print_r($object->getPrimaryKey(false), true));
            $object = null;
        }
        return $object;
    }

    /**
     * Custom collection loader that forces access policy checking.
     *
     * {@inheritdoc}
     */
    function loadCollection(& $xpdo, $className, $criteria, $cacheFlag= true) {
        $objCollection= array ();
        $fromCache = false;
        if (!$className= $xpdo->loadClass($className)) return $objCollection;
        $all= false;
        $rows= false;
        $fromCache= false;
        if ($criteria === null) {
            $all= true;
            if ($xpdo->_cacheEnabled && $cacheFlag) {
                $rows= $xpdo->fromCache($className . '_all');
            }
            if (!$rows) {
                $criteria= $xpdo->getCriteria($className, $criteria, $cacheFlag);
            } else {
                $fromCache= true;
            }
        }
        if (!is_object($criteria)) {
            $criteria= $xpdo->getCriteria($className, $criteria, $cacheFlag);
        }
        if (!$rows && is_object($criteria)) {
            $rows= xPDOObject :: _loadRows($xpdo, $className, $criteria);
        }
        if (is_array ($rows)) {
            foreach ($rows as $row) {
                modAccessibleObject :: _loadCollectionInstance($xpdo, $objCollection, $className, $criteria, $row, $fromCache, $cacheFlag);
            }
        } elseif ($rows) {
            while ($row = $rows->fetch(PDO_FETCH_ASSOC)) {
                modAccessibleObject :: _loadCollectionInstance($xpdo, $objCollection, $className, $criteria, $row, $fromCache, $cacheFlag);
            }
        }
        if ($xpdo->_cacheEnabled && $cacheFlag && !$fromCache) {
            if ($all) {
                $xpdo->toCache($className . '_all', $rows, $cacheFlag);
            } else {
                $xpdo->toCache($criteria, $rows, $cacheFlag);
            }
        }
        return $objCollection;
    }

    /**
     * Custom save that respects access policies.
     *
     * {@inheritdoc}
     */
    function save($cacheFlag = null) {
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
    function remove($ancestors= array ()) {
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
    function checkPolicy($criteria, $targets = null) {
        if ($criteria && is_a($this->xpdo, 'modX') && $this->xpdo->getSessionState() == MODX_SESSION_STATE_INITIALIZED) {
            if (!is_array($criteria) && is_scalar($criteria)) {
                $criteria = array("{$criteria}" => true);
            }
            $policy = $this->findPolicy();
            if (!empty($policy)) {
                $principal = is_object($this->xpdo->user) ? $this->xpdo->user->getAttributes($targets) : array();
                foreach ($policy as $policyAccess => $access) {
                    foreach ($access as $targetId => $targetPolicy) {
                        foreach ($targetPolicy as $principalId => $applicablePolicy) {
                            if ($this->xpdo->getDebug() === true)
                                $this->xpdo->log(MODX_LOG_LEVEL_DEBUG, 'target pk='. $this->getPrimaryKey() .'; evaluating policy: ' . print_r($applicablePolicy, 1) . ' against principal for user id=' . $this->xpdo->getLoginUserID() .': ' . print_r($principal, 1));
                            $principalPolicyData = array();
                            $principalAuthority = 9999;
                            if (isset($principal[$policyAccess][$targetId][$principalId])) {
                                $principalAuthority = intval($principal[$policyAccess][$targetId][$principalId]['authority']);
                                $principalPolicyData = $principal[$policyAccess][$targetId][$principalId]['policy'];
                                if ($principalAuthority <= $applicablePolicy['authority']) {
                                    if (!$applicablePolicy['policy']) {
                                        return true;
                                    }
                                    if ($matches = array_intersect_assoc($principalPolicyData, $applicablePolicy['policy'])) {
                                        if ($this->xpdo->getDebug() === true)
                                            $this->xpdo->log(MODX_LOG_LEVEL_DEBUG, 'Evaluating policy matches: ' . print_r($matches, 1));
                                        $matched = array_diff_assoc($criteria, $matches);
                                         if (empty($matched)) {
                                            return true;
                                        }
                                    }
                                }
                            } elseif ($principalId == '0' && $principalAuthority <= $applicablePolicy['authority'] && !$applicablePolicy['policy']) {
                                return true;
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
    function findPolicy($context = '') {
        return array();
    }
}
?>