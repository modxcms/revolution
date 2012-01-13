<?php
/**
 * Flushes permissions for the logged in user.
 *
 * @package modx
 * @subpackage processors.security.access
 */
class modFlushPermissionsProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('access_permissions');
    }

    public function process() {
        $ctxQuery = $this->modx->newQuery('modContext');
        $ctxQuery->select($this->modx->getSelectColumns('modContext', '', '', array('key')));
        if ($ctxQuery->prepare() && $ctxQuery->stmt->execute()) {
            $contexts = $ctxQuery->stmt->fetchAll(PDO::FETCH_COLUMN);
            if ($contexts) {
                $serialized = serialize($contexts);
                $this->modx->exec("UPDATE {$this->modx->getTableName('modUser')} SET {$this->modx->escape('session_stale')} = {$this->modx->quote($serialized)}");
            }
        }
        return $this->success();
    }
}
return 'modFlushPermissionsProcessor';