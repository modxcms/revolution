<?php
/**
 * Grab and download the error log
 *
 * @package modx
 * @subpackage processors.system.errorlog
 */
class modSystemErrorLogDownloadProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('error_log_view');
    }
    public function process() {
        $error_log_path = $this->modx->getOption('error_log_path');
        $file = MODX_BASE_PATH.$error_log_path.'error.log';
        if (!file_exists($file)) {
            return $this->failure();
        }
        header('Content-Type: application/force-download');
        header('Content-Length: ' . filesize($file));
        header('Content-Disposition: attachment; filename="error.'.time().'.log');
        ob_get_level() && @ob_end_flush();
        readfile($file);
        die();
    }
}
return 'modSystemErrorLogDownloadProcessor';