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
}