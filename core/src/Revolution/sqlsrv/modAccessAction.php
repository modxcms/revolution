<?php
namespace MODX\Revolution\sqlsrv;

use xPDO\xPDO;

class modAccessAction extends \MODX\Revolution\modAccessAction
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'access_actions',
        'extends' => 'MODX\\Revolution\\modAccess',
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
                'class' => 'MODX\\Revolution\\modAction',
                'local' => 'target',
                'foreign' => 'id',
                'owner' => 'foreign',
                'cardinality' => 'one',
            ),
        ),
    );

}
