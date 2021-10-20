<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modSystemSetting extends \MODX\Revolution\modSystemSetting
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'system_settings',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
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
                'dbtype' => 'varchar',
                'precision' => '50',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'pk',
            ),
            'value' => 
            array (
                'dbtype' => 'text',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'xtype' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '75',
                'phptype' => 'string',
                'null' => false,
                'default' => 'textfield',
            ),
            'namespace' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '40',
                'phptype' => 'string',
                'null' => false,
                'default' => 'core',
            ),
            'area' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'editedon' => 
            array (
                'dbtype' => 'timestamp',
                'phptype' => 'timestamp',
                'null' => true,
                'default' => NULL,
                'attributes' => 'ON UPDATE CURRENT_TIMESTAMP',
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
            'name_trans' => 'Entry.value',
            'description_trans' => 'Description.value',
        ]);
        $c->leftJoin(\MODX\Revolution\modLexiconEntry::class, 'Entry',
            "CONCAT('setting_',modSystemSetting.{$xpdo->escape('key')}) = Entry.name");
        $c->leftJoin(\MODX\Revolution\modLexiconEntry::class, 'Description',
            "CONCAT('setting_',modSystemSetting.{$xpdo->escape('key')},'_desc') = Description.name");
        $c->where($criteria);

        $count = $xpdo->getCount(\MODX\Revolution\modSystemSetting::class, $c);
        $c->sortby($xpdo->getSelectColumns(\MODX\Revolution\modSystemSetting::class, 'modSystemSetting', '', ['area']), 'ASC');
        foreach ($sort as $field => $dir) {
            $c->sortby($xpdo->getSelectColumns(\MODX\Revolution\modSystemSetting::class, 'modSystemSetting', '', [$field]), $dir);
        }
        if ((int)$limit > 0) {
            $c->limit((int)$limit, (int)$offset);
        }
        $c->prepare();

        return [
            'count' => $count,
            'collection' => $xpdo->getCollection(\MODX\Revolution\modSystemSetting::class, $c),
        ];
    }
}
