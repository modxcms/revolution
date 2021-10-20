<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modCategory extends \MODX\Revolution\modCategory
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'categories',
        'extends' => 'MODX\\Revolution\\modAccessibleSimpleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'parent' => 0,
            'category' => '',
            'rank' => 0,
        ),
        'fieldMeta' => 
        array (
            'parent' => 
            array (
                'dbtype' => 'int',
                'precision' => '10',
                'phptype' => 'integer',
                'attributes' => 'unsigned',
                'default' => 0,
                'index' => 'unique',
                'indexgrp' => 'category',
            ),
            'category' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '45',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'unique',
                'indexgrp' => 'category',
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
            'parent' => 
            array (
                'alias' => 'parent',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'parent' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'category' => 
            array (
                'alias' => 'category',
                'primary' => false,
                'unique' => true,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'parent' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                    'category' => 
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
            'Children' => 
            array (
                'class' => 'MODX\\Revolution\\modCategory',
                'local' => 'id',
                'foreign' => 'parent',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'Acls' => 
            array (
                'class' => 'MODX\\Revolution\\modAccessCategory',
                'local' => 'id',
                'foreign' => 'target',
                'owner' => 'local',
                'cardinality' => 'many',
            ),
            'Ancestors' => 
            array (
                'class' => 'MODX\\Revolution\\modCategoryClosure',
                'local' => 'id',
                'foreign' => 'ancestor',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'Descendants' => 
            array (
                'class' => 'MODX\\Revolution\\modCategoryClosure',
                'local' => 'id',
                'foreign' => 'descendant',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
        ),
        'aggregates' => 
        array (
            'Parent' => 
            array (
                'class' => 'MODX\\Revolution\\modCategory',
                'local' => 'parent',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
            'Chunks' => 
            array (
                'class' => 'MODX\\Revolution\\modChunk',
                'key' => 'id',
                'local' => 'id',
                'foreign' => 'category',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'Snippets' => 
            array (
                'class' => 'MODX\\Revolution\\modSnippet',
                'local' => 'id',
                'foreign' => 'category',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'Plugins' => 
            array (
                'class' => 'MODX\\Revolution\\modPlugin',
                'local' => 'id',
                'foreign' => 'category',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'Templates' => 
            array (
                'class' => 'MODX\\Revolution\\modTemplate',
                'local' => 'id',
                'foreign' => 'category',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'TemplateVars' => 
            array (
                'class' => 'MODX\\Revolution\\modTemplateVar',
                'local' => 'id',
                'foreign' => 'category',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'PropertySets' => 
            array (
                'class' => 'MODX\\Revolution\\modPropertySet',
                'local' => 'id',
                'foreign' => 'category',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'modChunk' => 
            array (
                'class' => 'MODX\\Revolution\\modChunk',
                'key' => 'id',
                'local' => 'id',
                'foreign' => 'category',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'modSnippet' => 
            array (
                'class' => 'MODX\\Revolution\\modSnippet',
                'local' => 'id',
                'foreign' => 'category',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'modPlugin' => 
            array (
                'class' => 'MODX\\Revolution\\modPlugin',
                'local' => 'id',
                'foreign' => 'category',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'modTemplate' => 
            array (
                'class' => 'MODX\\Revolution\\modTemplate',
                'local' => 'id',
                'foreign' => 'category',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'modTemplateVar' => 
            array (
                'class' => 'MODX\\Revolution\\modTemplateVar',
                'local' => 'id',
                'foreign' => 'category',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
            'modPropertySet' => 
            array (
                'class' => 'MODX\\Revolution\\modPropertySet',
                'local' => 'id',
                'foreign' => 'category',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
        ),
        'validation' => 
        array (
            'rules' => 
            array (
                'category' => 
                array (
                    'preventBlank' => 
                    array (
                        'type' => 'xPDOValidationRule',
                        'rule' => 'xPDO\\Validation\\xPDOMinLengthValidationRule',
                        'value' => '1',
                        'message' => 'category_err_ns_name',
                    ),
                ),
            ),
        ),
    );

}
