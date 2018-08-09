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
 * Clear the error log
 *
 * @package modx
 * @subpackage processors.system.errorlog
 */
class modSystemErrorLogClearProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('error_log_erase');
    }
    public function process() {
        $logTarget = $this->modx->getLogTarget();
        if (!is_array($logTarget)) {
            $logTarget = array('options' => array());
        }
        $filename = $this->modx->getOption('filename', $logTarget['options'], 'error.log', true);
        $filepath = $this->modx->getOption('filepath', $logTarget['options'], $this->modx->getCachePath() . xPDOCacheManager::LOG_DIR, true);
        $file = rtrim($filepath, '/').'/'.$filename;
        $content = '';
        $tooLarge = false;
        if (file_exists($file)) {
            /* @var modCacheManager $cacheManager */
            $cacheManager= $this->modx->getCacheManager();
            $cacheManager->writeFile($file,'');

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
return 'modSystemErrorLogClearProcessor';
