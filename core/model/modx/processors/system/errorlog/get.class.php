<?php
/**
 * Grab and output the error log
 *
 * @package modx
 * @subpackage processors.system.errorlog
 */
class modSystemErrorLogGetProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('error_log_view');
    }
    public function process() {
        $f = $this->modx->getOption(xPDO::OPT_CACHE_PATH).'logs/error.log';
        $content = '';
        if (file_exists($f)) {
            $content = @file_get_contents($f);
        }
        $la = array(
            'name' => $f,
            'log' => $content,
        );
        return $this->success('',$la);
    }
}
return 'modSystemErrorLogGetProcessor';