<?php
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
        $file = $this->modx->getOption(xPDO::OPT_CACHE_PATH).'logs/error.log';
        $content = '';
        if (file_exists($file)) {
            /* @var modCacheManager $cacheManager */
            $cacheManager= $this->modx->getCacheManager();
            $cacheManager->writeFile($file,' ');

            $content = @file_get_contents($file);
        }

        $la = array(
            'name' => $file,
            'log' => $content,
        );
        return $this->success('',$la);
    }
}
return 'modSystemErrorLogClearProcessor';
