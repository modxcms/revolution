<?php
/**
 * @package modx
 * @subpackage sqlite
 */
require_once (dirname(dirname(__FILE__)) . '/modevent.class.php');
/**
 * @package modx
 * @subpackage sqlite
 */
class modEvent_sqlite extends modEvent {
    public static function listEvents(xPDO &$xpdo, $plugin, array $criteria = array(), array $sort = array('id' => 'ASC'), $limit = 0, $offset = 0) {
        $c = $xpdo->newQuery('modEvent');
        $count = $xpdo->getCount('modEvent',$c);
        $c->select($xpdo->getSelectColumns('modEvent','modEvent'));
        $c->select(array(
            'IFNULL(modPluginEvent.pluginid,0) AS enabled',
            'modPluginEvent.priority AS priority',
            'modPluginEvent.propertyset AS propertyset',
        ));

        $c->leftJoin('modPluginEvent','modPluginEvent','
            modPluginEvent.event = modEvent.name
            AND modPluginEvent.pluginid = '.$plugin.'
        ');
        $c->where($criteria);
        foreach($sort as $field=> $dir) {
            $c->sortby($xpdo->getSelectColumns('modEvent','modEvent','',array($field)),$dir);
        }
        if ((int) $limit > 0) {
            $c->limit((int) $limit, (int) $offset);
        }
        //$c->prepare();
        return array(
            'count'=> $count,
            'collection'=> $xpdo->getCollection('modEvent',$c)
        );
    }
}
