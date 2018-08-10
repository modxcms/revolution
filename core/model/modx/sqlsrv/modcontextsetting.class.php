<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

require_once (dirname(__DIR__) . '/modcontextsetting.class.php');
/**
 * @package modx
 * @subpackage sqlsrv
 */
class modContextSetting_sqlsrv extends modContextSetting {
    public static function listSettings(xPDO &$xpdo, array $criteria = array(), array $sort = array('id' => 'ASC'), $limit = 0, $offset = 0) {
        /* build query */
        $c = $xpdo->newQuery('modContextSetting');
        $c->select(array(
            $xpdo->getSelectColumns('modContextSetting','modContextSetting'),
        ));
        $c->select(array(
            'Entry.value AS name_trans',
            'Description.value AS description_trans',
        ));
        $c->leftJoin('modLexiconEntry','Entry',"'setting_' + modContextSetting.{$xpdo->escape('key')} = Entry.name");
        $c->leftJoin('modLexiconEntry','Description',"'setting_' + modContextSetting.{$xpdo->escape('key')} + '_desc' = Description.name");
        $c->where($criteria);

        $count = $xpdo->getCount('modContextSetting',$c);
        $c->sortby($xpdo->getSelectColumns('modContextSetting','modContextSetting','',array('area')),'ASC');
        foreach($sort as $field=> $dir) {
            $c->sortby($xpdo->getSelectColumns('modContextSetting','modContextSetting','',array($field)),$dir);
        }
        if ((int) $limit > 0) {
            $c->limit((int) $limit, (int) $offset);
        }
        return array(
            'count'=> $count,
            'collection'=> $xpdo->getCollection('modContextSetting',$c)
        );
    }
}
?>
