<?php
/**
 * Flush all sessions
 *
 * @package modx
 * @subpackage processors.security
 */
class modSecurityFlushProcessor extends modProcessor {

    public function checkPermissions() {
        return $this->modx->hasPermission('flush_sessions');
    }
    
    public function process() {
        if ($this->modx->getOption('session_handler_class',null,'modSessionHandler') == 'modSessionHandler') {
            if (!$this->flushSessions()) {
                return $this->failure($this->modx->lexicon('flush_sessions_err'));
            }
        } else {
            return $this->failure($this->modx->lexicon('flush_sessions_not_supported'));
        }
        return $this->success();
    }

    public function flushSessions() {
        $flushed = true;
        $sessionTable = $this->modx->getTableName('modSession');
        if ($this->modx->query("TRUNCATE {$sessionTable}") == false) {
            $flushed = false;
        } else {
            $this->modx->user->endSession();
        }
        return $flushed;
    }
}
return 'modSecurityFlushProcessor';