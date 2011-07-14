<?php
/**
 * @package modx
 */
/**
 * Defines an access control policy between a principal and a target object.
 *
 * {@internal Implement derivatives to define a policy relationship to a
 * specific target class, which must extend modAccessibleObject or
 * modAccessibleSimpleObject, and must have an integer or string primary key.}
 *
 * @param string $target The target this ACL is attached to
 * @param string $principal_class The class key of the principal this ACL is attached to
 * @param int $principal The ID of the principal this ACL is attached to
 * @param int $authority The minimum authority level required to obtain this ACL
 * @param int $policy The ID of the modAccessPolicy that is attached to this ACL
 *
 * @abstract
 * @package modx
 */
class modAccess extends xPDOSimpleObject {}