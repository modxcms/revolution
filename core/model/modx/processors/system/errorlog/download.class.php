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
        $f = $this->modx->getOption(xPDO::OPT_CACHE_PATH).'logs/error.log';
        if (!file_exists($f)) {
            return $this->failure();
        }
        header('Content-Type: application/force-download');
        header('Content-Length: ' . filesize($f));
        header('Content-Disposition: attachment; filename="error.'.time().'.log');
        ob_get_level() && @ob_end_flush();
        readfile($f);
        die();
    }
}
return 'modSystemErrorLogDownloadProcessor';