<?php
/**
 * @package modx
 */
/**
 * Defines an access control policy between a principal and a modResourceGroup.
 *
 * {@inheritdoc}
 *
 * @property string $context_key The key of the Context this ACL refers to
 * @package modx
 */
class modAccessResourceGroup extends modAccess {
    /**
     * Load the attributes for the ACLs for the Resource Group
     *
     * @static
     * @param modX $modx A reference to the modX instance
     * @param string $context The context to load from. If empty, will use the current context.
     * @param int $userId The ID of the user to grab ACL records for.
     * @return array An array of loaded attributes
     */
    public static function loadAttributes(&$modx, $context = '', $userId = 0) {
        $attributes = array();
        if (empty($context)) {
            $context = $modx->context->get('key');
        }
        $enabled = (boolean) $modx->getOption('access_resource_group_enabled', null, true);
        if ($context !== $modx->context->get('key') && $modx->getContext($context)) {
            $enabled = (boolean) $modx->contexts[$context]->getOption('access_resource_group_enabled', $enabled);
        }
        if ($enabled) {
            $accessTable = $modx->getTableName('modAccessResourceGroup');
            $policyTable = $modx->getTableName('modAccessPolicy');
            $memberTable = $modx->getTableName('modUserGroupMember');
            $memberRoleTable = $modx->getTableName('modUserGroupRole');
            $legacyDocGroups = array();
            if ($userId > 0) {
                $sql = "SELECT acl.target, acl.principal, mr.authority, acl.policy, p.data FROM {$accessTable} acl " .
                        "LEFT JOIN {$policyTable} p ON p.id = acl.policy " .
                        "JOIN {$memberTable} mug ON acl.principal_class = 'modUserGroup' " .
                        "AND (acl.context_key = :context OR acl.context_key IS NULL OR acl.context_key = '') " .
                        "AND mug.member = :principal " .
                        "AND mug.user_group = acl.principal " .
                        "JOIN {$memberRoleTable} mr ON mr.id = mug.role " .
                        "AND mr.authority <= acl.authority " .
                        "ORDER BY acl.target, acl.principal, mr.authority, acl.policy";
                $bindings = array(
                    ':principal' => $userId,
                    ':context' => $context
                );
                $query = new xPDOCriteria($modx, $sql, $bindings);
                if ($query->stmt && $query->stmt->execute()) {
                    while ($row = $query->stmt->fetch(PDO::FETCH_ASSOC)) {
                        $attributes[$row['target']][] = array(
                            'principal' => $row['principal'],
                            'authority' => $row['authority'],
                            'policy' => $row['data'] ? $modx->fromJSON($row['data'], true) : array(),
                        );
                        $legacyDocGroups[$row['target']]= $row['target'];
                    }
                }
            } else {
                $sql = "SELECT acl.target, acl.principal, 0 AS authority, acl.policy, p.data FROM {$accessTable} acl " .
                        "LEFT JOIN {$policyTable} p ON p.id = acl.policy " .
                        "WHERE acl.principal_class = 'modUserGroup' " .
                        "AND acl.principal = 0 " .
                        "AND (acl.context_key = :context OR acl.context_key IS NULL OR acl.context_key = '') " .
                        "ORDER BY acl.target, acl.principal, acl.authority, acl.policy";
                $bindings = array(
                    ':context' => $context
                );
                $query = new xPDOCriteria($modx, $sql, $bindings);
                if ($query->stmt && $query->stmt->execute()) {
                    while ($row = $query->stmt->fetch(PDO::FETCH_ASSOC)) {
                        $attributes[$row['target']][] = array(
                            'principal' => 0,
                            'authority' => $row['authority'],
                            'policy' => $row['data'] ? $modx->fromJSON($row['data'], true) : array(),
                        );
                        $legacyDocGroups[$row['target']]= $row['target'];
                    }
                }
            }
            $_SESSION['modx.user.'.($userId > 0 ? (string) $userId : '0').'.resourceGroups'] = array(
                $context => array_values($legacyDocGroups),
            );
        }
        return $attributes;
    }
}