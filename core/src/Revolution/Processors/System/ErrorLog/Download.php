<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\ErrorLog;

use MODX\Revolution\Processors\Processor;
use xPDO\Cache\xPDOCacheManager;

/**
 * Grab and download the error log
 * @package MODX\Revolution\Processors\System\ErrorLog
 */
class Download extends Processor
{
    /**
     * @return mixed
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('error_log_view');
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $logTarget = $this->modx->getLogTarget();
        if (!is_array($logTarget)) {
            $logTarget = ['options' => []];
        }
        $filename = $this->modx->getOption('filename', $logTarget['options'], 'error.log', true);
        $filepath = $this->modx->getOption('filepath', $logTarget['options'],
            $this->modx->getCachePath() . xPDOCacheManager::LOG_DIR, true);
        $file = rtrim($filepath, '/') . '/' . $filename;
        if (!file_exists($file)) {
            return $this->failure();
        }
        header('Content-Type: application/force-download');
        header('Content-Length: ' . filesize($file));
        header('Content-Disposition: attachment; filename="error.' . time() . '.log');
        ob_get_level() && @ob_end_flush();
        readfile($file);
        die();
    }
}
