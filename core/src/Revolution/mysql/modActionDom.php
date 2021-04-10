<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modActionDom extends \MODX\Revolution\modActionDom
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'actiondom',
        'extends' => 'MODX\\Revolution\\modAccessibleSimpleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'set' => 0,
            'action' => '',
            'name' => '',
            'description' => NULL,
            'xtype' => '',
            'container' => '',
            'rule' => '',
            'value' => '',
            'constraint' => '',
            'constraint_field' => '',
            'constraint_class' => '',
            'active' => 1,
            'for_parent' => 0,
            'rank' => 0,
        ),
        'fieldMeta' => 
        array (
            'set' => 
            array (
                'dbtype' => 'int',
                'precision' => '11',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'action' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '191',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'index',
            ),
            'name' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '191',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'index',
            ),
            'description' => 
            array (
                'dbtype' => 'text',
                'phptype' => 'string',
            ),
            'xtype' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '100',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'container' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'rule' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '100',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'value' => 
            array (
                'dbtype' => 'text',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'constraint' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '255',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'constraint_field' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '100',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'constraint_class' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '100',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
            ),
            'active' => 
            array (
                'dbtype' => 'tinyint',
                'precision' => '1',
                'attributes' => 'unsigned',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 1,
                'index' => 'index',
            ),
            'for_parent' => 
            array (
                'dbtype' => 'tinyint',
                'precision' => '1',
                'attributes' => 'unsigned',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'rank' => 
            array (
                'dbtype' => 'int',
                'precision' => '11',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
        ),
        'indexes' => 
        array (
            'set' => 
            array (
                'alias' => 'set',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'set' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'action' => 
            array (
                'alias' => 'action',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'action' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'name' => 
            array (
                'alias' => 'name',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'name' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'active' => 
            array (
                'alias' => 'active',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'active' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'for_parent' => 
            array (
                'alias' => 'for_parent',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'for_parent' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'rank' => 
            array (
                'alias' => 'rank',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'rank' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
        ),
        'composites' => 
        array (
            'Access' => 
            array (
                'class' => 'MODX\\Revolution\\modAccessActionDom',
                'local' => 'id',
                'foreign' => 'target',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
        ),
        'aggregates' => 
        array (
            'FCSet' => 
            array (
                'class' => 'MODX\\Revolution\\modFormCustomizationSet',
                'local' => 'set',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
        ),
    );

}
