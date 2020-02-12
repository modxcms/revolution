<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

require_once (strtr(realpath(dirname(__DIR__)), '\\', '/') . '/modtransportpackage.class.php');
/**
 * @package modx
 * @subpackage mysql
 */
class modTransportPackage_mysql extends modTransportPackage {
    public static function listPackages(modX &$modx, $workspace, $limit = 0, $offset = 0,$search = '') {
        $result = array('collection' => array(), 'total' => 0);
        $c = $modx->newQuery('transport.modTransportPackage');
        $c->leftJoin('transport.modTransportProvider','Provider', array("modTransportPackage.provider = Provider.id"));
        $c->where(array(
            'workspace' => $workspace,
        ));
        $c->where(array(
            "(SELECT
                `signature`
              FROM {$modx->getTableName('modTransportPackage')} AS `latestPackage`
              WHERE `latestPackage`.`package_name` = `modTransportPackage`.`package_name`
              ORDER BY
                `latestPackage`.`version_major` DESC,
                `latestPackage`.`version_minor` DESC,
                `latestPackage`.`version_patch` DESC,
                IF(`release` = '' OR `release` = 'ga' OR `release` = 'pl','z',IF(`release` = 'dev','a',`release`)) DESC,
                `latestPackage`.`release_index` DESC
              LIMIT 1) = `modTransportPackage`.`signature`",
        ));
        if (!empty($search)) {
            $c->where(array(
                'modTransportPackage.signature:LIKE' => '%'.$search.'%',
                'OR:modTransportPackage.package_name:LIKE' => '%'.$search.'%',
            ));
        }
        $result['total'] = $modx->getCount('modTransportPackage',$c);
        $c->select(array(
            'modTransportPackage.*',
        ));
        $c->select('`Provider`.`name` AS `provider_name`');
        $c->sortby('`modTransportPackage`.`signature`', 'ASC');
        if ($limit > 0) $c->limit($limit, $offset);
        $result['collection'] = $modx->getCollection('transport.modTransportPackage',$c);
        return $result;
    }

    public static function listPackageVersions(modX &$modx, $criteria, $limit = 0, $offset = 0) {
        $result = array('collection' => array(), 'total' => 0);
        $c = $modx->newQuery('transport.modTransportPackage');
        $c->select($modx->getSelectColumns('transport.modTransportPackage','modTransportPackage'));
        $c->select(array('Provider.name AS provider_name'));
        $c->leftJoin('transport.modTransportProvider','Provider');
        $c->where($criteria);
        $result['total'] = $modx->getCount('modTransportPackage',$c);
        $c->sortby('modTransportPackage.version_major', 'DESC');
        $c->sortby('modTransportPackage.version_minor', 'DESC');
        $c->sortby('modTransportPackage.version_patch', 'DESC');
        $c->sortby('IF(modTransportPackage.release = \'\' OR modTransportPackage.release = \'ga\' OR modTransportPackage.release = \'pl\',\'z\',IF(modTransportPackage.release = \'dev\',\'a\',modTransportPackage.release))','DESC');
        $c->sortby('modTransportPackage.release_index', 'DESC');
        if((int)$limit > 0) {
            $c->limit((int)$limit, (int)$offset);
        }
        $result['collection'] = $modx->getCollection('transport.modTransportPackage',$c);
        return $result;
    }
}
