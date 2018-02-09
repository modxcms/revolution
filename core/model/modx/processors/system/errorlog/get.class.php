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
        $filename = !empty($this->modx->getOption('error_log_filename')) ? $this->modx->getOption('error_log_filename') : 'error.log';
        $filepath = !empty($this->modx->getOption('error_log_path')) ? $this->modx->getOption('error_log_path') : $this->modx->getCachePath() . xPDOCacheManager::LOG_DIR;
        $f = $filepath.$filename;
        $content = '';
        $tooLarge = false;
        if (file_exists($f)) {
            $size = round(@filesize($f) / 1000 / 1000,2);
            if ($size > 1) {
                $tooLarge = true;
            } else {
                $content = @file_get_contents($f);
            }
        }
        $la = array(
            'name' => $f,
            'log' => $content,
            'tooLarge' => $tooLarge,
        );
        return $this->success('',$la);
    }
}
return 'modSystemErrorLogGetProcessor';