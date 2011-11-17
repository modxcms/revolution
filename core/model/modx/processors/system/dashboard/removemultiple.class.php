<?php
/**
 * Removes multiple Dashboards
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.system.dashboard
 */
class modDashboardRemoveMultipleProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('dashboards');
    }

    public function getLanguageTopics() {
        return array('dashboards');
    }

    public function process() {
        $dashboards = $this->getProperty('dashboards',null);

        if (empty($dashboards)) {
            return $this->failure($this->modx->lexicon('dashboard_err_ns'));
        }

        $dashboardIds = is_array($dashboards) ? $dashboards : explode(',',$dashboards);
        foreach ($dashboardIds as $dashboardId) {
            /** @var modDashboard $dashboard */
            $dashboard = $this->modx->getObject('modDashboard',$dashboardId);
            if (empty($dashboard)) { continue; }

            if ($dashboard->remove() == false) {
                $this->modx->log(modX::LOG_LEVEL_ERROR,$this->modx->lexicon('dashboard_err_remove'));
                continue;
            }
            $this->modx->logManagerAction('dashboard_remove','modDashboard',$dashboard->get('id'));
        }

        return $this->success();
    }
}
return 'modDashboardRemoveMultipleProcessor';
