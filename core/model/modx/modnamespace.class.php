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
    /**
     * Overrides xPDOObject::get to provide placeholder substitution for the namespace path.
     * 
     * {@inheritdoc}
     */
    public function get($k,$format = null,$formatTemplate = null) {
        $v = parent :: get($k,$format,$formatTemplate);
        if ($k == 'path') {
            $v = str_replace(array(
                '{core_path}',
                '{base_path}',
                '{assets_path}',
            ),array(
                $this->xpdo->getOption('core_path',null,MODX_CORE_PATH),
                $this->xpdo->getOption('base_path',null,MODX_BASE_PATH),
                $this->xpdo->getOption('assets_path',null,MODX_ASSETS_PATH),
            ),$v);
        }
        return $v;
    }
}