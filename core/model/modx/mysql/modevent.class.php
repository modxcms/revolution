<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

include_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/../modevent.class.php');
/**
 * @package modx
 * @subpackage mysql
 */
class modEvent_mysql extends modEvent {
    public static function listEvents(xPDO &$xpdo, $plugin, array $criteria = array(), array $sort = array('id' => 'ASC'), $limit = 0, $offset = 0) {
        $c = $xpdo->newQuery('modEvent');
        $count = $xpdo->getCount('modEvent',$c);
        $c->select($xpdo->getSelectColumns('modEvent','modEvent'));
        $c->select(array(
            'IF(ISNULL(modPluginEvent.pluginid),0,1) AS enabled',
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
        return array(
            'count'=> $count,
            'collection'=> $xpdo->getCollection('modEvent',$c)
        );
    }
}
