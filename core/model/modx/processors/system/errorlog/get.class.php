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
        $error_log_path = $this->modx->getOption('error_log_path');
        $file = MODX_BASE_PATH.$error_log_path.'error.log';
        $content = '';
        $tooLarge = false;
        if (file_exists($file)) {
            $size = round(@filesize($file) / 1000 / 1000,2);
            if ($size > 1) {
                $tooLarge = true;
            } else {
                $content = @file_get_contents($file);
            }
        }
        $la = array(
            'name' => $file,
            'log' => $content,
            'tooLarge' => $tooLarge,
        );
        return $this->success('',$la);
    }
}
return 'modSystemErrorLogGetProcessor';