<?php
/**
 * @package modx
 * @subpackage sqlite
 */
include_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/../modcontextsetting.class.php');
/**
 * @package modx
 * @subpackage sqlite
 */
class modContextSetting_sqlite extends modContextSetting {
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
        $c->groupby('modContextSetting.' . $xpdo->escape('context_key') . ', modContextSetting.' . $xpdo->escape('key')); // лечение ALEX

        $count = $xpdo->getCount('modContextSetting',$c);
        $c->sortby($xpdo->getSelectColumns('modContextSetting','modContextSetting','',array('area')),'ASC');
        foreach($sort as $field=> $dir) {
            $c->sortby($xpdo->getSelectColumns('modContextSetting','modContextSetting','',array($field)),$dir);
        }
        if ((int) $limit > 0) {
            $c->limit((int) $limit, (int) $offset);
        }
        //$c->prepare();
        return array(
            'count'=> $count,
            'collection'=> $xpdo->getCollection('modContextSetting',$c)
        );
    }
}