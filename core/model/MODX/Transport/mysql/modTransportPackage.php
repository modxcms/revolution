<?php

namespace MODX\Transport\mysql;

use MODX\MODX;

class modTransportPackage extends \MODX\Transport\modTransportPackage
{

    public static $metaMap = [
        'package' => 'MODX\\Transport',
        'version' => '3.0',
        'table' => 'transport_packages',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' =>
            [
                'signature' => null,
                'created' => null,
                'updated' => null,
                'installed' => null,
                'state' => 1,
                'workspace' => 0,
                'provider' => 0,
                'disabled' => 0,
                'source' => null,
                'manifest' => null,
                'attributes' => null,
                'package_name' => null,
                'metadata' => null,
                'version_major' => 0,
                'version_minor' => 0,
                'version_patch' => 0,
                'release' => '',
                'release_index' => 0,
            ],
        'fieldMeta' =>
            [
                'signature' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'index' => 'pk',
                    ],
                'created' =>
                    [
                        'dbtype' => 'datetime',
                        'phptype' => 'datetime',
                        'null' => false,
                    ],
                'updated' =>
                    [
                        'dbtype' => 'timestamp',
                        'phptype' => 'timestamp',
                        'attributes' => 'ON UPDATE CURRENT_TIMESTAMP',
                    ],
                'installed' =>
                    [
                        'dbtype' => 'datetime',
                        'phptype' => 'datetime',
                    ],
                'state' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'attributes' => 'unsigned',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 1,
                    ],
                'workspace' =>
                    [
                        'dbtype' => 'integer',
                        'precision' => '10',
                        'attributes' => 'unsigned',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'fk',
                    ],
                'provider' =>
                    [
                        'dbtype' => 'integer',
                        'precision' => '10',
                        'attributes' => 'unsigned',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'fk',
                    ],
                'disabled' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'attributes' => 'unsigned',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'source' =>
                    [
                        'dbtype' => 'tinytext',
                        'phptype' => 'string',
                    ],
                'manifest' =>
                    [
                        'dbtype' => 'text',
                        'phptype' => 'array',
                    ],
                'attributes' =>
                    [
                        'dbtype' => 'mediumtext',
                        'phptype' => 'array',
                    ],
                'package_name' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'index' => 'index',
                    ],
                'metadata' =>
                    [
                        'dbtype' => 'text',
                        'phptype' => 'array',
                    ],
                'version_major' =>
                    [
                        'dbtype' => 'smallint',
                        'attributes' => 'unsigned',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'version_minor' =>
                    [
                        'dbtype' => 'smallint',
                        'attributes' => 'unsigned',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'version_patch' =>
                    [
                        'dbtype' => 'smallint',
                        'attributes' => 'unsigned',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
                'release' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'index',
                    ],
                'release_index' =>
                    [
                        'dbtype' => 'smallint',
                        'attributes' => 'unsigned',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
            ],
        'indexes' =>
            [
                'PRIMARY' =>
                    [
                        'alias' => 'PRIMARY',
                        'primary' => true,
                        'unique' => true,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'signature' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'workspace' =>
                    [
                        'alias' => 'workspace',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'workspace' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'provider' =>
                    [
                        'alias' => 'provider',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'provider' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'disabled' =>
                    [
                        'alias' => 'disabled',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'disabled' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'package_name' =>
                    [
                        'alias' => 'package_name',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'package_name' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'version_major' =>
                    [
                        'alias' => 'version_major',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'version_major' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'version_minor' =>
                    [
                        'alias' => 'version_minor',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'version_minor' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'version_patch' =>
                    [
                        'alias' => 'version_patch',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'version_patch' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'release' =>
                    [
                        'alias' => 'release',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'release' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'release_index' =>
                    [
                        'alias' => 'release_index',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'release_index' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
            ],
        'aggregates' =>
            [
                'Workspace' =>
                    [
                        'class' => 'MODX\\modWorkspace',
                        'local' => 'workspace',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'Provider' =>
                    [
                        'class' => 'transport.modTransportProvider',
                        'local' => 'provider',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
            ],
    ];


    public static function listPackages(MODX &$modx, $workspace, $limit = 0, $offset = 0, $search = '')
    {
        $result = ['collection' => [], 'total' => 0];
        $c = $modx->newQuery('transport.modTransportPackage');
        $c->leftJoin('transport.modTransportProvider', 'Provider', ["modTransportPackage.provider = Provider.id"]);
        $c->where([
            'workspace' => $workspace,
        ]);
        $c->where([
            "(SELECT
                `signature`
              FROM {$modx->getTableName('transport.modTransportPackage')} AS `latestPackage`
              WHERE `latestPackage`.`package_name` = `modTransportPackage`.`package_name`
              ORDER BY
                `latestPackage`.`version_major` DESC,
                `latestPackage`.`version_minor` DESC,
                `latestPackage`.`version_patch` DESC,
                IF(`release` = '' OR `release` = 'ga' OR `release` = 'pl','z',IF(`release` = 'dev','a',`release`)) DESC,
                `latestPackage`.`release_index` DESC
              LIMIT 1) = `modTransportPackage`.`signature`",
        ]);
        if (!empty($search)) {
            $c->where([
                'modTransportPackage.signature:LIKE' => '%' . $search . '%',
                'OR:modTransportPackage.package_name:LIKE' => '%' . $search . '%',
            ]);
        }
        $result['total'] = $modx->getCount('transport.modTransportPackage', $c);
        $c->select([
            'modTransportPackage.*',
        ]);
        $c->select('`Provider`.`name` AS `provider_name`');
        $c->sortby('`modTransportPackage`.`signature`', 'ASC');

        if ($limit > 0) {
            $c->limit($limit, $offset);
        }
        $result['collection'] = $modx->getCollection('transport.modTransportPackage', $c);

        return $result;
    }


    public static function listPackageVersions(MODX &$modx, $criteria, $limit = 0, $offset = 0)
    {
        $result = ['collection' => [], 'total' => 0];
        $c = $modx->newQuery('transport.modTransportPackage');
        $c->select($modx->getSelectColumns('transport.modTransportPackage', 'modTransportPackage'));
        $c->select(['Provider.name AS provider_name']);
        $c->leftJoin('transport.modTransportProvider', 'Provider');
        $c->where($criteria);
        $result['total'] = $modx->getCount('transport.modTransportPackage', $c);
        $c->sortby('modTransportPackage.version_major', 'DESC');
        $c->sortby('modTransportPackage.version_minor', 'DESC');
        $c->sortby('modTransportPackage.version_patch', 'DESC');
        $c->sortby('IF(modTransportPackage.release = "" OR modTransportPackage.release = "ga" OR modTransportPackage.release = "pl","z",IF(modTransportPackage.release = "dev","a",modTransportPackage.release))', 'DESC');
        $c->sortby('modTransportPackage.release_index', 'DESC');
        if ((int)$limit > 0) {
            $c->limit((int)$limit, (int)$offset);
        }
        $result['collection'] = $modx->getCollection('transport.modTransportPackage', $c);

        return $result;
    }
}
