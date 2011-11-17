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
        if ($this->modx->getUser()) {
            $this->modx->user->getAttributes(array(), '', true);
        }
        return $this->success();
    }
}
return 'modFlushPermissionsProcessor';