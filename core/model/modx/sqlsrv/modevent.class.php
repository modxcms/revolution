<?php
/**
 * @package modx
 * @subpackage sqlsrv
 */
require_once (dirname(dirname(__FILE__)) . '/modevent.class.php');
/**
 * @package modx
 * @subpackage sqlsrv
 */
class modEvent_sqlsrv extends modEvent {
    public static function listEvents(xPDO &$xpdo, $plugin, array $criteria = array(), array $sort = array('id' => 'ASC'), $limit = 0, $offset = 0) {
        $c = $xpdo->newQuery('modEvent');
        $count = $xpdo->getCount('modEvent',$c);
        $c->leftJoin('modPluginEvent','modPluginEvent','
            modPluginEvent.event = modEvent.name
            AND modPluginEvent.pluginid = '.$plugin.'
        ');
        $c->select(array(
            'modEvent.*',
            'CASE WHEN modPluginEvent.pluginid IS NULL THEN 0 ELSE 1 END AS enabled',
            'modPluginEvent.priority AS priority',
            'modPluginEvent.propertyset AS propertyset',
        ));
        $c->where($criteria);
        foreach($sort as $field=> $dir) {
            $c->sortby($xpdo->getSelectColumns('modEvent','modEvent','',array($field)),$dir);
        }
        if ((int) $limit > 0) {
            $c->limit((int) $limit, (int) $offset);
        }
        $c->prepare();
        return array(
            'count'=> $count,
            'collection'=> $xpdo->getCollection('modEvent',$c)
        );
    }
}
