<?php

namespace MODX\Revolution\Sources;

use MODX\Revolution\modAccess;
use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modUserGroup;
use MODX\Revolution\modUserGroupMember;
use MODX\Revolution\modUserGroupRole;
use MODX\Revolution\modX;
use PDO;
use xPDO\Om\xPDOCriteria;

/**
 * Handles ACL integration into Media Sources
 *
 * @property string $context_key
 *
 * @package MODX\Revolution\Sources
 */
class modAccessMediaSource extends modAccess
{
    /**
     * Load the attributes for the ACLs for the context
     *
     * @static
     *
     * @param modX   $modx    A reference to the modX instance
     * @param string $context The context to load from. If empty, will use the current context.
     * @param int    $userId  The ID of the user to grab ACL records for.
     *
     * @return array An array of loaded attributes
     */
    public static function loadAttributes(&$modx, $context = '', $userId = 0)
    {
        $attributes = [];
        if (empty($context)) {
            $context = $modx->context->get('key');
        }
        $enabled = (boolean)$modx->getOption('access_media_source_enabled', null, true);
        if ($context !== $modx->context->get('key') && $modx->getContext($context)) {
            $enabled = (boolean)$modx->contexts[$context]->getOption('access_media_source_enabled', $enabled);
        }
        if ($enabled) {
            $accessTable = $modx->getTableName(modAccessMediaSource::class);
            $policyTable = $modx->getTableName(modAccessPolicy::class);
            $memberTable = $modx->getTableName(modUserGroupMember::class);
            $memberRoleTable = $modx->getTableName(modUserGroupRole::class);
            if ($userId > 0) {
                $sql = "SELECT acl.target, acl.principal, mr.authority, acl.policy, p.data FROM {$accessTable} acl " .
                    "LEFT JOIN {$policyTable} p ON p.id = acl.policy " .
                    "JOIN {$memberTable} mug ON acl.principal_class = {$modx->quote(modUserGroup::class)} " .
                    "AND mug.member = :principal " .
                    "AND mug.user_group = acl.principal " .
                    "JOIN {$memberRoleTable} mr ON mr.id = mug.role " .
                    "AND mr.authority <= acl.authority " .
                    "ORDER BY acl.target, acl.principal, mr.authority, acl.policy";
                $bindings = [
                    ':principal' => $userId,
                ];
                $query = new xPDOCriteria($modx, $sql, $bindings);
                if ($query->stmt && $query->stmt->execute()) {
                    while ($row = $query->stmt->fetch(PDO::FETCH_ASSOC)) {
                        $attributes[$row['target']][] = [
                            'principal' => $row['principal'],
                            'authority' => $row['authority'],
                            'policy' => $row['data'] ? $modx->fromJSON($row['data'], true) : [],
                        ];
                    }
                }
            } else {
                $sql = "SELECT acl.target, acl.principal, 0 AS authority, acl.policy, p.data FROM {$accessTable} acl " .
                    "LEFT JOIN {$policyTable} p ON p.id = acl.policy " .
                    "WHERE acl.principal_class = {$modx->quote(modUserGroup::class)} " .
                    "AND acl.principal = 0 " .
                    "ORDER BY acl.target, acl.principal, acl.authority, acl.policy";
                $query = new xPDOCriteria($modx, $sql);
                if ($query->stmt && $query->stmt->execute()) {
                    while ($row = $query->stmt->fetch(PDO::FETCH_ASSOC)) {
                        $attributes[$row['target']][] = [
                            'principal' => 0,
                            'authority' => $row['authority'],
                            'policy' => $row['data'] ? $modx->fromJSON($row['data'], true) : [],
                        ];
                    }
                }
            }
        }

        return $attributes;
    }
}
