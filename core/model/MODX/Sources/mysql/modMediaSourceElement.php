<?php

namespace MODX\Sources\mysql;

use xPDO\xPDO;

class modMediaSourceElement extends \MODX\Sources\modMediaSourceElement
{

    public static $metaMap = [
        'package' => 'MODX\\Sources',
        'version' => '3.0',
        'table' => 'media_sources_elements',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' =>
            [
                'source' => 0,
                'object_class' => 'modTemplateVar',
                'object' => 0,
                'context_key' => 'web',
            ],
        'fieldMeta' =>
            [
                'source' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '11',
                        'attributes' => 'unsigned',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'pk',
                    ],
                'object_class' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'modTemplateVar',
                        'index' => 'pk',
                    ],
                'object' =>
                    [
                        'dbtype' => 'int',
                        'precision' => '11',
                        'attributes' => 'unsigned',
                        'phptype' => 'integer',
                        'null' => false,
                        'default' => 0,
                        'index' => 'pk',
                    ],
                'context_key' =>
                    [
                        'dbtype' => 'varchar',
                        'precision' => '100',
                        'phptype' => 'string',
                        'null' => false,
                        'default' => 'web',
                        'index' => 'pk',
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
                                'source' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                                'object' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                                'object_class' =>
                                    [
                                        'length' => '',
                                        'collation' => 'A',
                                        'null' => false,
                                    ],
                                'context_key' =>
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
                'Source' =>
                    [
                        'class' => 'MODX\\Sources\\modMediaSource',
                        'local' => 'source',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'Element' =>
                    [
                        'class' => 'MODX\\modElement',
                        'local' => 'object',
                        'foreign' => 'id',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
                'Context' =>
                    [
                        'class' => 'MODX\\modContext',
                        'local' => 'context_key',
                        'foreign' => 'key',
                        'cardinality' => 'one',
                        'owner' => 'foreign',
                    ],
            ],
    ];
}
