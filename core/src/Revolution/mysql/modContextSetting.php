<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modContextSetting extends \MODX\Revolution\modContextSetting
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'context_setting',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'context_key' => NULL,
            'key' => NULL,
            'value' => NULL,
            'xtype' => 'textfield',
            'namespace' => 'core',
            'area' => '',
            'editedon' => NULL,
        ),
        'fieldMeta' => 
        array (
            'context_key' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '191',
                'phptype' => 'string',
                'null' => false,
                'index' => 'pk',
            ),
            'key' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '50',
                'phptype' => 'string',
                'null' => false,
                'index' => 'pk',
            ),
            'value' => 
            array (
                'dbtype' => 'mediumtext',
                'phptype' => 'string',
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
                'columns' => 
                array (
                    'context_key' => 
                    array (
                        'collation' => 'A',
                        'null' => false,
                    ),
                    'key' => 
                    array (
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
        ),
        'aggregates' => 
        array (
            'Context' => 
            array (
                'class' => 'MODX\\Revolution\\modContext',
                'key' => 'context_key',
                'local' => 'context_key',
                'foreign' => 'key',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
            'SystemSetting' => 
            array (
                'class' => 'MODX\\Revolution\\modSystemSetting',
                'key' => 'key',
                'local' => 'key',
                'foreign' => 'key',
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
        $c = $xpdo->newQuery(\MODX\Revolution\modContextSetting::class);
        $c->distinct();
        $c->select([
            $xpdo->getSelectColumns(\MODX\Revolution\modContextSetting::class, 'modContextSetting'),
        ]);
        $c->select([
            'Entry.value AS name_trans',
            'Description.value AS description_trans',
        ]);
        $c->leftJoin(\MODX\Revolution\modLexiconEntry::class, 'Entry',
            "CONCAT('setting_',modContextSetting.{$xpdo->escape('key')}) = Entry.name");
        $c->leftJoin(\MODX\Revolution\modLexiconEntry::class, 'Description',
            "CONCAT('setting_',modContextSetting.{$xpdo->escape('key')},'_desc') = Description.name");
        $c->where($criteria);

        $count = $xpdo->getCount(\MODX\Revolution\modContextSetting::class, $c);
        $c->sortby($xpdo->getSelectColumns(\MODX\Revolution\modContextSetting::class, 'modContextSetting', '', ['area']), 'ASC');
        foreach ($sort as $field => $dir) {
            $c->sortby($xpdo->getSelectColumns(\MODX\Revolution\modContextSetting::class, 'modContextSetting', '', [$field]), $dir);
        }
        if ((int)$limit > 0) {
            $c->limit((int)$limit, (int)$offset);
        }

        return [
            'count' => $count,
            'collection' => $xpdo->getCollection(\MODX\Revolution\modContextSetting::class, $c),
        ];
    }
}
