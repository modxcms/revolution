<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

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
        $logTarget = $this->modx->getLogTarget();
        if (!is_array($logTarget)) {
            $logTarget = array('options' => array());
        }
        $filename = $this->modx->getOption('filename', $logTarget['options'], 'error.log', true);
        $filepath = $this->modx->getOption('filepath', $logTarget['options'], $this->modx->getCachePath() . xPDOCacheManager::LOG_DIR, true);
        $file = rtrim($filepath, '/').'/'.$filename;
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
