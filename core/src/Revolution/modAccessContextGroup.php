<?php

namespace MODX\Revolution;

use PDO;
use xPDO\Om\xPDOCriteria;

/**
 * An ACL granting User Group access to all Contexts in a Context Group.
 *
 * Runtime attributes are keyed by member Context key so they merge into the
 * same principal bucket as {@see modAccessContext}.
 *
 * @package MODX\Revolution
 */
class modAccessContextGroup extends modAccess
{
    /**
     * Load Context ACL attributes implied by Context Group ACL rows.
     *
     * @param modX   $modx
     * @param string $context Unused for query scope; kept for loadAttributes signature parity.
     * @param int    $userId
     *
     * @return array Attributes keyed by Context key
     */
    public static function loadAttributes(&$modx, $context = '', $userId = 0)
    {
        $accessTable = $modx->getTableName(self::class);
        $contextTable = $modx->getTableName(modContext::class);
        $policyTable = $modx->getTableName(modAccessPolicy::class);
        $memberTable = $modx->getTableName(modUserGroupMember::class);
        $memberRoleTable = $modx->getTableName(modUserGroupRole::class);
        $principalClass = $modx->quote(modUserGroup::class);
        $contextKey = "ctx.{$modx->escape('key')}";

        $from = "FROM {$accessTable} acl " .
            "LEFT JOIN {$policyTable} p ON p.id = acl.policy " .
            "JOIN {$contextTable} ctx ON ctx.context_group = acl.target " .
            "AND ctx.context_group > 0 AND acl.target > 0 ";

        if ($userId > 0) {
            $sql = "SELECT {$contextKey} AS target, acl.principal, mr.authority, acl.policy, p.data {$from}" .
                "JOIN {$memberTable} mug ON acl.principal_class = {$principalClass} " .
                "AND mug.member = :principal AND mug.user_group = acl.principal " .
                "JOIN {$memberRoleTable} mr ON mr.id = mug.role AND mr.authority <= acl.authority " .
                "ORDER BY target, acl.principal, mr.authority, acl.policy";
            $bindings = [':principal' => $userId];
        } else {
            $sql = "SELECT {$contextKey} AS target, acl.principal, 0 AS authority, acl.policy, p.data {$from}" .
                "WHERE acl.principal_class = {$principalClass} AND acl.principal = 0 " .
                "ORDER BY target, acl.principal, acl.authority, acl.policy";
            $bindings = null;
        }

        return self::queryAttributes($modx, $sql, $bindings, $userId <= 0);
    }

    /**
     * @param modX       $modx
     * @param string     $sql
     * @param array|null $bindings
     * @param bool       $anonymousPrincipal
     *
     * @return array
     */
    private static function queryAttributes(modX $modx, string $sql, ?array $bindings, bool $anonymousPrincipal): array
    {
        $attributes = [];
        $query = $bindings === null
            ? new xPDOCriteria($modx, $sql)
            : new xPDOCriteria($modx, $sql, $bindings);

        if (!$query->stmt || !$query->stmt->execute()) {
            return $attributes;
        }

        while ($row = $query->stmt->fetch(PDO::FETCH_ASSOC)) {
            $attributes[$row['target']][] = [
                'principal' => $anonymousPrincipal ? 0 : $row['principal'],
                'authority' => $row['authority'],
                'policy' => $row['data'] ? $modx->fromJSON($row['data'], true) : [],
            ];
        }

        return $attributes;
    }
}
