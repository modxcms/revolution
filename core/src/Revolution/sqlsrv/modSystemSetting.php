<?php
namespace MODX\Revolution\sqlsrv;

use xPDO\xPDO;

class modSystemSetting extends \MODX\Revolution\modSystemSetting
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'system_settings',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' =>
        array (
            'key' => '',
            'value' => '',
            'xtype' => 'textfield',
            'namespace' => 'core',
            'area' => '',
            'editedon' => NULL,
        ),
        'fieldMeta' =>
        array (
            'key' =>
            array (
                'dbtype' => 'nvarchar',
                'precision' => '50',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'pk',
            ),
            'value' =>
            array (
                'dbtype' => 'nvarchar',
                'precision' => 'max',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'xtype' =>
            array (
                'dbtype' => 'nvarchar',
                'precision' => '75',
                'phptype' => 'string',
                'null' => false,
                'default' => 'textfield',
            ),
            'namespace' =>
            array (
                'dbtype' => 'nvarchar',
                'precision' => '40',
                'phptype' => 'string',
                'null' => false,
                'default' => 'core',
            ),
            'area' =>
            array (
                'dbtype' => 'nvarchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'editedon' =>
            array (
                'dbtype' => 'datetime',
                'phptype' => 'timestamp',
                'null' => true,
            ),
        ),
        'indexes' =>
        array (
            'PRIMARY' =>
            array (
                'alias' => 'PRIMARY',
                'primary' => true,
                'unique' => true,
                'type' => 'BTREE',
                'columns' =>
                array (
                    'key' =>
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
        ),
        'aggregates' =>
        array (
            'ContextSetting' =>
            array (
                'class' => 'MODX\\Revolution\\modContextSetting',
                'local' => 'key',
                'foreign' => 'key',
                'cardinality' => 'one',
                'owner' => 'local',
            ),
            'Namespace' =>
            array (
                'class' => 'MODX\\Revolution\\modNamespace',
                'local' => 'namespace',
                'foreign' => 'name',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
        ),
    );

    public static function listSettings(
        xPDO &$xpdo,
        array $criteria = [],
        array $sort = ['id' => 'ASC'],
        $limit = 0,
        $offset = 0
    ) {
        /* build query */
        $c = $xpdo->newQuery(\MODX\Revolution\modSystemSetting::class);
        $c->select([
            $xpdo->getSelectColumns(\MODX\Revolution\modSystemSetting::class, 'modSystemSetting'),
        ]);
        $c->select([
            'Entry.value AS name_trans',
            'Description.value AS description_trans',
        ]);
        $c->leftJoin(\MODX\Revolution\modLexiconEntry::class, 'Entry', "'setting_' + modSystemSetting.{$xpdo->escape('key')} = Entry.name");
        $c->leftJoin(\MODX\Revolution\modLexiconEntry::class, 'Description', "'setting_' + modSystemSetting.{$xpdo->escape('key')} + '_desc' = Description.name");
        $c->where($criteria);

        $count = $xpdo->getCount(\MODX\Revolution\modSystemSetting::class, $c);
        $c->sortby($xpdo->getSelectColumns(\MODX\Revolution\modSystemSetting::class, 'modSystemSetting', '', ['area']), 'ASC');
        foreach ($sort as $field => $dir) {
            $c->sortby($xpdo->getSelectColumns(\MODX\Revolution\modSystemSetting::class, 'modSystemSetting', '', [$field]), $dir);
        }
        if ((int)$limit > 0) {
            $c->limit((int)$limit, (int)$offset);
        }

        return [
            'count' => $count,
            'collection' => $xpdo->getCollection(\MODX\Revolution\modSystemSetting::class, $c),
        ];
    }
}
