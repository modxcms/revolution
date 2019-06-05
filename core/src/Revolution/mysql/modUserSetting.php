<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modUserSetting extends \MODX\Revolution\modUserSetting
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'user_settings',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' =>
        array (
            'user' => 0,
            'key' => '',
            'value' => NULL,
            'xtype' => 'textfield',
            'namespace' => 'core',
            'area' => '',
            'editedon' => NULL,
        ),
        'fieldMeta' =>
        array (
            'user' =>
            array (
                'dbtype' => 'int',
                'precision' => '11',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'pk',
            ),
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
                'precision' => '191',
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
                    'user' =>
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
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
            'User' =>
            array (
                'class' => 'MODX\\Revolution\\modUser',
                'local' => 'user',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
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
        $c = $xpdo->newQuery('modUserSetting');
        $c->select([
            $xpdo->getSelectColumns('modUserSetting', 'modUserSetting'),
            'Entry.value AS name_trans',
            'Description.value AS description_trans',
        ]);
        $c->leftJoin('modLexiconEntry', 'Entry', "CONCAT('setting_',modUserSetting.`key`) = Entry.name");
        $c->leftJoin('modLexiconEntry', 'Description',
            "CONCAT('setting_',modUserSetting.`key`,'_desc') = Description.name");
        $c->where($criteria);
        $count = $xpdo->getCount('modUserSetting', $c);
        $c->sortby($xpdo->getSelectColumns('modUserSetting', 'modUserSetting', '', ['area']), 'ASC');
        foreach ($sort as $field => $dir) {
            $c->sortby($xpdo->getSelectColumns('modUserSetting', 'modUserSetting', '', [$field]), $dir);
        }
        if ((int)$limit > 0) {
            $c->limit((int)$limit, (int)$offset);
        }

        return [
            'count' => $count,
            'collection' => $xpdo->getCollection('modUserSetting', $c),
        ];
    }
}
