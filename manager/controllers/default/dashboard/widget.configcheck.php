<?php
/**
 * @package modx
 * @subpackage dashboard
 */
/**
 * Renders the config check box
 * @package modx
 * @subpackage dashboard
 */
class modDashboardWidgetConfigCheck extends modDashboardWidgetInterface {
    public $cssBlockClass = 'dashboard-block-variable';
    
    public function render() {
        $o = '';
        /* do some config checks */
        $modx =& $this->modx;
        $config_check_results = '';
        $success = include $this->modx->getOption('processors_path') . 'system/config_check.inc.php';
        if (!$success) {
            $o = $config_check_results;
        }
        return $o;
    }
}
return 'modDashboardWidgetConfigCheck';