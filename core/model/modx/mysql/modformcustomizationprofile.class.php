<?php
/**
 * @package modx
 * @subpackage mysql
 */
require_once (dirname(__DIR__) . '/modformcustomizationprofile.class.php');
/**
 * @package modx
 * @subpackage mysql
 */
class modFormCustomizationProfile_mysql extends modFormCustomizationProfile {
    public static function listProfiles(xPDO &$xpdo, array $criteria = array(), array $sort = array('id' => 'ASC'), $limit = 0, $offset = 0) {
        /* query for profiles */
        $c = $xpdo->newQuery('modFormCustomizationProfile');
        $c->select(array(
            'modFormCustomizationProfile.*',
        ));
        $c->select('
            (SELECT GROUP_CONCAT(UserGroup.name) FROM '.$xpdo->getTableName('modUserGroup').' AS UserGroup
                INNER JOIN '.$xpdo->getTableName('modFormCustomizationProfileUserGroup').' AS fcpug
                ON fcpug.usergroup = UserGroup.id
             WHERE fcpug.profile = modFormCustomizationProfile.id
            ) AS usergroups
        ');
        $c->where($criteria,null,2);// also log issue in remine to look at this usage of where()
        $count = $xpdo->getCount('modFormCustomizationProfile',$c);

        foreach($sort as $field=> $dir) {
            $c->sortby($xpdo->getSelectColumns('modFormCustomizationProfile','modFormCustomizationProfile','',array($field)),$dir);
        }
        if ((int) $limit > 0) {
            $c->limit((int) $limit, (int) $offset);
        }
        return array(
            'count'=> $count,
            'collection'=> $xpdo->getCollection('modFormCustomizationProfile',$c)
        );
    }
}
