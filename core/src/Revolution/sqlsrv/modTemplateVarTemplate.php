<?php
namespace MODX\Revolution\sqlsrv;

use xPDO\xPDO;

class modTemplateVarTemplate extends \MODX\Revolution\modTemplateVarTemplate
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'site_tmplvar_templates',
        'extends' => 'xPDO\\Om\\xPDOObject',
        'fields' => 
        array (
            'tmplvarid' => 0,
            'templateid' => 0,
            'rank' => 0,
        ),
        'fieldMeta' => 
        array (
            'tmplvarid' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'pk',
            ),
            'templateid' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'pk',
            ),
            'rank' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
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
                    'tmplvarid' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                    'templateid' => 
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
                'key' => 'id',
                'local' => 'tmplvarid',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
            'Template' => 
            array (
                'class' => 'MODX\\Revolution\\modTemplate',
                'key' => 'id',
                'local' => 'templateid',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
        ),
    );

}
