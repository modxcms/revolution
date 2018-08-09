<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

require_once (dirname(__DIR__) . '/modformcustomizationprofile.class.php');
/**
 * @package modx
 * @subpackage sqlsrv
 */
class modFormCustomizationProfile_sqlsrv extends modFormCustomizationProfile {
    public static function listProfiles(xPDO &$xpdo, array $criteria = array(), array $sort = array('id' => 'ASC'), $limit = 0, $offset = 0) {
        $objCollection= array ();

        /* query for profiles */
        $c = $xpdo->newQuery('modFormCustomizationProfile');
        $c->select(array(
            $xpdo->getSelectColumns('modFormCustomizationProfile','modFormCustomizationProfile'),
        ));
        $c->where($criteria,null,2);// also log issue in remine to look at this usage of where()
        $count = $xpdo->getCount('modFormCustomizationProfile',$c);

        foreach($sort as $field=> $dir) {
            $c->sortby($xpdo->getSelectColumns('modFormCustomizationProfile','modFormCustomizationProfile','',array($field)),$dir);
        }
        if ((int) $limit > 0) {
            $c->limit((int) $limit, (int) $offset);
        }

        $rows= xPDOObject :: _loadRows($xpdo, 'modFormCustomizationProfile', $c);
        $rowsArray = $rows->fetchAll(PDO::FETCH_ASSOC);
        $rows->closeCursor();
        foreach($rowsArray as $row) {
            $objCollection[] = $xpdo->call('modFormCustomizationProfile', '_loadInstance', array(&$xpdo, 'modFormCustomizationProfile', $c, $row));
        }
        unset($row, $rowsArray);
        return array(
            'count'=> $count,
            'collection'=> $objCollection
        );
    }

    public static function _loadInstance(& $xpdo, $className, $criteria, $row) {
        $sql = "SELECT gr.[name]
             FROM {$xpdo->config['table_prefix']}membergroup_names AS gr,
              {$xpdo->config['table_prefix']}fc_profiles_usergroups AS pu,
              {$xpdo->config['table_prefix']}fc_profiles AS pr
             WHERE gr.id = pu.usergroup
               AND pu.profile = pr.id
               AND pr.id = {$row['id']}
               ORDER BY gr.name";
        $groupNamesStatement = $xpdo->query($sql);
        $groupNamesArray = $groupNamesStatement->fetchAll(PDO::FETCH_COLUMN, 0);
        $row['usergroups'] = implode(', ', $groupNamesArray);
        return parent :: _loadInstance($xpdo, $className, $criteria, $row);
    }
}
?>
