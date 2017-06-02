<?php
/**
 * @package modx
 * @subpackage transport.sqlsrv
 */
require_once (dirname(__DIR__) . '/modtransportpackage.class.php');
/**
 * @package modx
 * @subpackage sqlsrv
 */
class modTransportPackage_sqlsrv extends modTransportPackage {
    public static function listPackages(modX &$modx, $workspace, $limit = 0, $offset = 0,$search = '') {
        $result = array('collection' => array(), 'total' => 0);
        $c = $modx->newQuery('transport.modTransportPackage');
        $c->leftJoin('transport.modTransportProvider','Provider', array("modTransportPackage.provider = Provider.id"));
        $c->where(array(
            'workspace' => $workspace,
        ));
        $c->where(array(
            "(SELECT TOP 1
                latestPackage.signature
              FROM {$modx->getTableName('modTransportPackage')} AS latestPackage
              WHERE latestPackage.package_name = modTransportPackage.package_name
              ORDER BY
                 latestPackage.version_major DESC,
                 latestPackage.version_minor DESC,
                 latestPackage.version_patch DESC,
                 CASE WHEN latestPackage.release = '' OR latestPackage.release = 'ga' OR latestPackage.release = 'pl' THEN 'z' ELSE latestPackage.release END DESC,
                 latestPackage.release_index DESC
              ) = modTransportPackage.signature",
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
        $c->select('Provider.name AS provider_name');
        $c->sortby('modTransportPackage.signature', 'ASC');
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
        $c->sortby("CASE WHEN modTransportPackage.release = '' OR modTransportPackage.release = 'ga' OR modTransportPackage.release = 'pl'
            THEN 'z'
            ELSE modTransportPackage.release
          END",'DESC');
        $c->sortby('modTransportPackage.release_index', 'DESC');
        if((int)$limit > 0) {
            $c->limit((int)$limit, (int)$offset);
        }
        $result['collection'] = $modx->getCollection('transport.modTransportPackage',$c);
        return $result;
    }

}
