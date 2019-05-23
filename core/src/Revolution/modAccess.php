<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOObject;
use xPDO\Om\xPDOSimpleObject;

/**
 * Defines an access control policy between a principal and a target object.
 *
 * Implement derivatives to define a policy relationship to a
 * specific target class, which must extend modAccessibleObject or
 * modAccessibleSimpleObject, and must have an integer or string primary key.
 *
 * @param string $target          The target this ACL is attached to
 * @param string $principal_class The class key of the principal this ACL is attached to
 * @param int    $principal       The ID of the principal this ACL is attached to
 * @param int    $authority       The minimum authority level required to obtain this ACL
 * @param int    $policy          The ID of the modAccessPolicy that is attached to this ACL
 *
 * @abstract
 *
 * @package MODX\Revolution
 */
class modAccess extends xPDOSimpleObject
{
    /**
     * Override getOne to get the appropriate Principal class.
     *
     * @param      $alias
     * @param null $criteria
     * @param bool $cacheFlag
     *
     * @return null|xPDOObject
     */
    public function & getOne($alias, $criteria = null, $cacheFlag = true)
    {
        $object = null;
        if ($alias === 'Principal') {
            if ($fkdef = $this->getFKDefinition($alias)) {
                $k = $fkdef['local'];
                $fk = $fkdef['foreign'];
                if (isset ($this->_relatedObjects[$alias])) {
                    if (is_object($this->_relatedObjects[$alias])) {
                        $object = &$this->_relatedObjects[$alias];

                        return $object;
                    }
                }
                if ($criteria === null) {
                    $criteria = [
                        $fk => $this->get($k),
                    ];
                }
                $fkdef['class'] = $this->get('principal_class');
                if ($object = $this->xpdo->getObject($fkdef['class'], $criteria, $cacheFlag)) {
                    $this->_relatedObjects[$alias] = $object;
                }
            }
        } else {
            $object = parent::getOne($alias, $criteria, $cacheFlag);
        }

        return $object;
    }
}
