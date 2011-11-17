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
}