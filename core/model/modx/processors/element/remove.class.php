<?php
/**
 * Abstract class for Remove Element processors. To be extended for each derivative element type.
 *
 * @abstract
 * @package modx
 * @subpackage processors.element
 */
abstract class modElementRemoveProcessor extends modObjectRemoveProcessor {
    public function cleanup() {
        $this->clearCache();
    }
    public function clearCache() {
        $this->modx->cacheManager->refresh();
    }

    public function cleanupStaticFiles() {
        /* Remove file. */
        $count = $this->modx->getCount($this->classKey, array('static_file' => $this->staticFile));
        if ($this->staticFilePath && $count === 0) {
            @unlink($this->staticFilePath);
        }

        /* Check if parent directory is empty, if so remove parent directory. */
        $pathinfo = pathinfo($this->staticFilePath);

        $this->cleanupStaticDirectories($pathinfo['dirname']);
    }

    public function cleanupStaticDirectories($dirname) {
        $contents = array_diff(scandir($dirname), array('..', '.', '.DS_Store'));


        @unlink($dirname .'/.DS_Store');
        if (count($contents) === 0) {
            if (is_dir($dirname)) {
                if (rmdir($dirname)) {
                    /* Check if parent directory is also empty. */
                    $this->cleanupStaticDirectories(dirname($dirname));
                }
            }
        }
    }
}