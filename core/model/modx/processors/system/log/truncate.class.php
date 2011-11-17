<?php
/**
 * Clears the manager log actions
 *
 * @package modx
 * @subpackage processors.system.log
 */
class modSystemLogTruncateProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('logs');
    }
    public function process() {
        $this->modx->exec("TRUNCATE {$this->modx->getTableName('modManagerLog')}");
        return $this->success();
    }
}
return 'modSystemLogTruncateProcessor';
