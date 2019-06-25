<?php
namespace MODX\Revolution\sqlsrv;

use xPDO\xPDO;

class modTemplateVarResourceGroup extends \MODX\Revolution\modTemplateVarResourceGroup
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'site_tmplvar_access',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
        'fields' => 
        array (
            'tmplvarid' => 0,
            'documentgroup' => 0,
        ),
        'fieldMeta' => 
        array (
            'tmplvarid' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
            ),
            'documentgroup' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
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
            'ResourceGroup' => 
            array (
                'class' => 'MODX\\Revolution\\modResourceGroup',
                'local' => 'documentgroup',
                'foreign' => 'id',
                'cardinality' => 'one',
                'owner' => 'foreign',
            ),
        ),
    );

}
