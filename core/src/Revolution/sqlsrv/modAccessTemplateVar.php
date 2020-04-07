<?php
namespace MODX\Revolution\sqlsrv;

use xPDO\xPDO;

class modAccessTemplateVar extends \MODX\Revolution\modAccessTemplateVar
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'access_templatevars',
        'extends' => 'MODX\\Revolution\\modAccessElement',
        'fields' => 
        array (
        ),
        'fieldMeta' => 
        array (
        ),
        'aggregates' => 
        array (
            'Target' => 
            array (
                'class' => 'MODX\\Revolution\\modTemplateVar',
                'local' => 'target',
                'foreign' => 'id',
                'owner' => 'foreign',
                'cardinality' => 'one',
            ),
        ),
    );

}
