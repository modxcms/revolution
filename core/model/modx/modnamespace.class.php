<?php
/**
 * modNamespace
 *
 * @package modx
 */
/**
 * Represents a Component in the MODx framework.
 *
 * @package modx
 */
class modNamespace extends xPDOObject {
    /**
     *
     * @param <type> $k
     * @param <type> $format
     * @param <type> $formatTemplate
     * @return <type>
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