<?php

namespace MODX\mysql;


class modResourceGroup extends \MODX\modResourceGroup
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'documentgroup_names',
        'extends' => 'MODX\\modAccessibleSimpleObject',
        'fields' =>
            [
                'name' => '',
                'private_memgroup' => 0,
                'private_webgroup' => 0,
            ],
        'fieldMeta' =>
            [
                'name' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '191',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'unique',
                    ],
                'private_memgroup' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'attributes' => 'unsigned',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
                    ],
                'private_webgroup' =>
                    [
                        'dbtype' => 'tinyint',
                        'precision' => '1',
                        'attributes' => 'unsigned',
                        'phptype' => 'boolean',
                        'null' => false,
                        'default' => 0,
                    ],
            ],
        'indexes' =>
            [
                'name' =>
                    [
                        'alias' => 'name',
                        'primary' => false,
                        'unique' => true,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'name' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
            ],
        'composites' =>
            [
                'ResourceGroupResources' =>
                    [
                        'class' => 'MODX\\modResourceGroupResource',
                        'local' => 'id',
                        'foreign' => 'document_group',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'TemplateVarResourceGroups' =>
                    [
                        'class' => 'MODX\\modTemplateVarResourceGroup',
                        'local' => 'id',
                        'foreign' => 'documentgroup',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'Acls' =>
                    [
                        'class' => 'MODX\\modAccessResourceGroup',
                        'local' => 'id',
                        'foreign' => 'target',
                        'owner' => 'local',
                        'cardinality' => 'many',
                    ],
            ],
    ];
}
