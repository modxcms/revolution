<?php
/**
 * @package modx
 * @subpackage sqlite
 */
require_once (dirname(dirname(__FILE__)) . '/modusersetting.class.php');
/**
 * @package modx
 * @subpackage sqlite
 */
class modUserSetting_sqlite extends modUserSetting {
    public static function listSettings(xPDO &$xpdo, array $criteria = array(), array $sort = array('id' => 'ASC'), $limit = 0, $offset = 0) {
        $c = $xpdo->newQuery('modUserSetting');
        $c->select(array(
            $xpdo->getSelectColumns('modUserSetting','modUserSetting'),
            'Entry.value AS name_trans',
            'Description.value AS description_trans',
        ));
        $c->leftJoin('modLexiconEntry','Entry',"'setting_' + modUserSetting.{$xpdo->escape('key')} = Entry.name");
        $c->leftJoin('modLexiconEntry','Description',"'setting_' + modUserSetting.{$xpdo->escape('key')} + '_desc' = Description.name");
        $c->where($criteria);
        $c->groupby('modUserSetting.' . $xpdo->escape('user') . ', modUserSetting.' . $xpdo->escape('key')); // лечение ALEX
        $count = $xpdo->getCount('modUserSetting',$c);
        $c->sortby($xpdo->getSelectColumns('modUserSetting','modUserSetting','',array('area')),'ASC');
        foreach($sort as $field=> $dir) {
            $c->sortby($xpdo->getSelectColumns('modUserSetting','modUserSetting','',array($field)),$dir);
        }
        if ((int) $limit > 0) {
            $c->limit((int) $limit, (int) $offset);
        }
       // $c->prepare();
        return array(
            'count'=> $count,
            'collection'=> $xpdo->getCollection('modUserSetting',$c)
        );
    }
}
?>