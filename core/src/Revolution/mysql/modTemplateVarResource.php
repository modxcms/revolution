<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modTemplateVarResource extends \MODX\Revolution\modTemplateVarResource
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'site_tmplvar_contentvalues',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'tmplvarid' => 0,
            'contentid' => 0,
            'value' => NULL,
            'createdon' => 0,
            'editedon' => 0,
        ),
        'fieldMeta' => 
        array (
            'tmplvarid' => 
            array (
                'dbtype' => 'int',
                'precision' => '10',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'contentid' => 
            array (
                'dbtype' => 'int',
                'precision' => '10',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'index',
            ),
            'value' =>
            array (
                'dbtype' => 'mediumtext',
                'phptype' => 'string',
                'null' => false,
            ),
            'createdon' =>
            array (
                'dbtype' => 'int',
                'precision' => '20',
                'phptype' => 'timestamp',
                'null' => false,
                'default' => 0,
            ),
            'editedon' =>
            array (
                'dbtype' => 'int',
                'precision' => '20',
                'phptype' => 'timestamp',
                'null' => false,
                'default' => 0,
            ),
        ),
        'indexes' => 
        array (
            'tmplvarid' => 
            array (
                'alias' => 'tmplvarid',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'tmplvarid' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'contentid' => 
            array (
                'alias' => 'contentid',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'contentid' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'tv_cnt' => 
            array (
                'alias' => 'tv_cnt',
                'primary' => false,
                'unique' => true,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'tmplvarid' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                    'contentid' => 
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
            'TemplateVar' => 
            array (
                'class' => 'MODX\\Revolution\\modTemplateVar',
                'local' => 'tmplvarid',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
            'Resource' => 
            array (
                'class' => 'MODX\\Revolution\\modResource',
                'local' => 'contentid',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
        ),
    );

}
