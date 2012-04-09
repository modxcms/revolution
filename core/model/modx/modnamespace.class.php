<?php
/**
 * modNamespace
 *
 * @package modx
 */
/**
 * Represents a Component in the MODX framework. Isolates controllers, lexicons and other logic into the virtual
 * containment space defined by the path of the namespace.
 *
 * @property string $name The key of the namespace
 * @property string $path The absolute path of the namespace. May use {core_path}, {base_path} or {assets_path} as
 * placeholders for the path.
 *
 * @package modx
 */
class modNamespace extends xPDOObject {
    public function getCorePath() {
        $path = $this->get('path');
        return $this->xpdo->call('modNamespace','translatePath',array(&$this->xpdo,$path));
    }

    public function getAssetsPath() {
        $path = $this->get('assets_path');
        return $this->xpdo->call('modNamespace','translatePath',array(&$this->xpdo,$path));
    }

    public static function translatePath(xPDO &$xpdo,$path) {
        return str_replace(array(
            '{core_path}',
            '{base_path}',
            '{assets_path}',
        ),array(
            $xpdo->getOption('core_path',null,MODX_CORE_PATH),
            $xpdo->getOption('base_path',null,MODX_BASE_PATH),
            $xpdo->getOption('assets_path',null,MODX_ASSETS_PATH),
        ),$path);
    }
}