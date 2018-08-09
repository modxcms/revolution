<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

require_once (dirname(__DIR__) . '/modusersetting.class.php');
/**
 * @package modx
 * @subpackage sqlsrv
 */
class modUserSetting_sqlsrv extends modUserSetting {
    public static function listSettings(xPDO &$xpdo, array $criteria = array(), array $sort = array('id' => 'ASC'), $limit = 0, $offset = 0) {
        $c = $xpdo->newQuery('modUserSetting');
        $c->select(array(
            $xpdo->getSelectColumns('modUserSetting','modUserSetting'),
            'Entry.value AS name_trans',
            'Description.value AS description_trans',
        ));
        $c->leftJoin('modLexiconEntry','Entry',"'setting_' + modUserSetting.[key] = Entry.name");
        $c->leftJoin('modLexiconEntry','Description',"'setting_' + modUserSetting.[key] + '_desc' = Description.name");
        $c->where($criteria);
        $count = $xpdo->getCount('modUserSetting',$c);
        $c->sortby($xpdo->getSelectColumns('modUserSetting','modUserSetting','',array('area')),'ASC');
        foreach($sort as $field=> $dir) {
            $c->sortby($xpdo->getSelectColumns('modUserSetting','modUserSetting','',array($field)),$dir);
        }
        if ((int) $limit > 0) {
            $c->limit((int) $limit, (int) $offset);
        }
        return array(
            'count'=> $count,
            'collection'=> $xpdo->getCollection('modUserSetting',$c)
        );
    }
}
?>
