<?php

namespace MODX\mysql;


class modCategory extends \MODX\modCategory
{

    public static $metaMap = [
        'package' => 'MODX',
        'version' => '3.0',
        'table' => 'categories',
        'extends' => 'MODX\\modAccessibleSimpleObject',
        'fields' =>
            [
                'parent' => 0,
                'category' => '',
                'rank' => 0,
            ],
        'fieldMeta' =>
            [
                'parent' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '10',
                        'phptype' => 'integer',
                        'attributes' => 'unsigned',
                        'default' => 0,
                        'index' => 'unique',
                        'indexgrp' => 'category',
                    ],
                'category' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '45',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => '',
                        'index' => 'unique',
                        'indexgrp' => 'category',
                    ],
                'rank' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '11',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'index',
                    ],
            ],
        'indexes' =>
            [
                'parent' =>
                    [
                        'alias' => 'parent',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'parent' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'category' =>
                    [
                        'alias' => 'category',
                        'primary' => false,
                        'unique' => true,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'parent' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                                'category' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                            ],
                    ],
                'rank' =>
                    [
                        'alias' => 'rank',
                        'primary' => false,
                        'unique' => false,
                        'type' => 'BTREE',
                        'columns' =>
                            [
                                'rank' =>
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
                'Children' =>
                    [
                        'class' => 'MODX\\modCategory',
                        'local' => 'id',
                        'foreign' => 'parent',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'Acls' =>
                    [
                        'class' => 'MODX\\modAccessCategory',
                        'local' => 'id',
                        'foreign' => 'target',
                        'owner' => 'local',
                        'cardinality' => 'many',
                    ],
                'Ancestors' =>
                    [
                        'class' => 'MODX\\modCategoryClosure',
                        'local' => 'id',
                        'foreign' => 'ancestor',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'Descendants' =>
                    [
                        'class' => 'MODX\\modCategoryClosure',
                        'local' => 'id',
                        'foreign' => 'descendant',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
            ],
        'aggregates' =>
            [
                'Parent' =>
                    [
                        'class' => 'MODX\\modCategory',
                        'local' => 'parent',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'Chunks' =>
                    [
                        'class' => 'MODX\\modChunk',
                        'key' => 'id',
                        'local' => 'id',
                        'foreign' => 'category',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'Snippets' =>
                    [
                        'class' => 'MODX\\modSnippet',
                        'local' => 'id',
                        'foreign' => 'category',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'Plugins' =>
                    [
                        'class' => 'MODX\\modPlugin',
                        'local' => 'id',
                        'foreign' => 'category',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'Templates' =>
                    [
                        'class' => 'MODX\\modTemplate',
                        'local' => 'id',
                        'foreign' => 'category',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'TemplateVars' =>
                    [
                        'class' => 'MODX\\modTemplateVar',
                        'local' => 'id',
                        'foreign' => 'category',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'PropertySets' =>
                    [
                        'class' => 'MODX\\modPropertySet',
                        'local' => 'id',
                        'foreign' => 'category',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'modChunk' =>
                    [
                        'class' => 'MODX\\modChunk',
                        'key' => 'id',
                        'local' => 'id',
                        'foreign' => 'category',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'modSnippet' =>
                    [
                        'class' => 'MODX\\modSnippet',
                        'local' => 'id',
                        'foreign' => 'category',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'modPlugin' =>
                    [
                        'class' => 'MODX\\modPlugin',
                        'local' => 'id',
                        'foreign' => 'category',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'modTemplate' =>
                    [
                        'class' => 'MODX\\modTemplate',
                        'local' => 'id',
                        'foreign' => 'category',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'modTemplateVar' =>
                    [
                        'class' => 'MODX\\modTemplateVar',
                        'local' => 'id',
                        'foreign' => 'category',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
                'modPropertySet' =>
                    [
                        'class' => 'MODX\\modPropertySet',
                        'local' => 'id',
                        'foreign' => 'category',
                        'cardinality' => 'many',
                        'owner' => 'local',
                    ],
            ],
        'validation' =>
            [
                'rules' =>
                    [
                        'category' =>
                            [
                                'preventBlank' =>
                                    [
                                        'type' => 'xPDOValidationRule',
                                        'rule' => 'xPDOMinLengthValidationRule',
                                        'value' => '1',
                                        'message' => 'category_err_ns_name',
                                    ],
                            ],
                    ],
            ],
    ];
}
