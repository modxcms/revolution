<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modResourceGroup extends \MODX\Revolution\modResourceGroup
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'documentgroup_names',
        'extends' => 'MODX\\Revolution\\modAccessibleSimpleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'name' => '',
            'private_memgroup' => 0,
            'private_webgroup' => 0,
        ),
        'fieldMeta' => 
        array (
            'name' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '191',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'unique',
            ),
            'private_memgroup' => 
            array (
                'dbtype' => 'tinyint',
                'precision' => '1',
                'attributes' => 'unsigned',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 0,
            ),
            'private_webgroup' => 
            array (
                'dbtype' => 'tinyint',
                'precision' => '1',
                'attributes' => 'unsigned',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 0,
            ),
        ),
        'indexes' => 
        array (
            'name' => 
            array (
                'alias' => 'name',
                'primary' => false,
                'unique' => true,
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
        ),
        'composites' => 
        array (
            'ResourceGroupResources' => 
            array (
                'class' => 'MODX\\Revolution\\modResourceGroupResource',
                'local' => 'id',
                'foreign' => 'document_group',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'TemplateVarResourceGroups' => 
            array (
                'class' => 'MODX\\Revolution\\modTemplateVarResourceGroup',
                'local' => 'id',
                'foreign' => 'documentgroup',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'Acls' => 
            array (
                'class' => 'MODX\\Revolution\\modAccessResourceGroup',
                'local' => 'id',
                'foreign' => 'target',
                'owner' => 'local',
                'cardinality' => 'many',
            ),
        ),
    );

}
