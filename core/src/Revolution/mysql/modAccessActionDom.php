<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modAccessActionDom extends \MODX\Revolution\modAccessActionDom
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'access_actiondom',
        'extends' => 'MODX\\Revolution\\modAccess',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
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
                'class' => 'MODX\\Revolution\\modActionDom',
                'local' => 'target',
                'foreign' => 'id',
                'owner' => 'foreign',
                'cardinality' => 'one',
            ),
        ),
    );

}
