<?php
/**
 * @package modx
 * @subpackage transport.mysql
 */
require_once (strtr(realpath(dirname(dirname(__FILE__))), '\\', '/') . '/modtransportpackage.class.php');
class modTransportPackage_mysql extends modTransportPackage {
    public static function listPackages(modX &$modx, $workspace, $limit = 0, $offset = 0) {
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
                 IF(`release` = '' OR `release` = 'ga' OR `release` = 'pl','z',`release`) DESC,
                 `latestPackage`.`release_index` DESC
              LIMIT 1) = `modTransportPackage`.`signature`",
        ));
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
}