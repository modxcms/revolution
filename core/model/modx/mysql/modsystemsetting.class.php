<?php
/**
 * @package modx
 * @subpackage mysql
 */
include_once (strtr(realpath(dirname(__FILE__)), '\\', '/') . '/../modsystemsetting.class.php');
/**
 * @package modx
 * @subpackage mysql
 */
class modSystemSetting_mysql extends modSystemSetting {
    public static function listSettings(xPDO &$xpdo, array $criteria = array(), array $sort = array('id' => 'ASC'), $limit = 0, $offset = 0) {
        /* build query */
        $c = $xpdo->newQuery('modSystemSetting');
        $c->select(array(
            $xpdo->getSelectColumns('modSystemSetting','modSystemSetting'),
        ));
        $c->select(array(
            'name_trans' => 'Entry.value',
            'description_trans' => 'Description.value',
        ));
        $c->leftJoin('modLexiconEntry','Entry',"CONCAT('setting_',modSystemSetting.{$xpdo->escape('key')}) = Entry.name");
        $c->leftJoin('modLexiconEntry','Description',"CONCAT('setting_',modSystemSetting.{$xpdo->escape('key')},'_desc') = Description.name");
        $c->where($criteria);

        $count = $xpdo->getCount('modSystemSetting',$c);
        $c->sortby($xpdo->getSelectColumns('modSystemSetting','modSystemSetting','',array('area')),'ASC');
        foreach($sort as $field=> $dir) {
            $c->sortby($xpdo->getSelectColumns('modSystemSetting','modSystemSetting','',array($field)),$dir);
        }
        if ((int) $limit > 0) {
            $c->limit((int) $limit, (int) $offset);
        }
        $c->prepare();
        return array(
            'count'=> $count,
            'collection'=> $xpdo->getCollection('modSystemSetting',$c)
        );
    }
}