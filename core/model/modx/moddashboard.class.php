<?php
/**
 * @package modx
 * @subpackage mysql
 */
/**
 * @package modx
 * @subpackage mysql
 */
class modDashboard extends xPDOSimpleObject {
    /**
     * Get the default MODX dashboard
     * @static
     * @param xPDO $xpdo A reference to an xPDO instance
     * @return An|null|object
     */
    public static function getDefaultDashboard(xPDO &$xpdo) {
        $defaultDashboard = $xpdo->getObject('modDashboard',array(
            'id' => 1,
        ));
        if (!$defaultDashboard) {
            $defaultDashboard = $xpdo->getObject('modDashboard',array(
                'name' => 'Default',
            ));
        }
        return $defaultDashboard;
    }

    /**
     * Render the Dashboard
     *
     * @param modManagerController $controller
     * @return string
     */
    public function render(modManagerController $controller) {
        $c = $this->xpdo->newQuery('modDashboardWidgetPlacement');
        $c->where(array(
            'dashboard' => $this->get('id'),
        ));
        $c->sortby('rank','ASC');
        $placements = $this->getMany('Placements',$c);
        $output = array();
        /** @var modDashboardWidgetPlacement $placement */
        foreach ($placements as $placement) {
            /** @var modDashboardWidget $widget */
            $widget = $placement->getOne('Widget');
            if ($widget) {
                $output[] = $widget->getContent($controller);
            }
        }
        return implode("\n",$output);
    }
}