<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modAccessContext extends \MODX\Revolution\modAccessContext
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'access_context',
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
                'class' => 'MODX\\Revolution\\modContext',
                'local' => 'target',
                'foreign' => 'key',
                'owner' => 'foreign',
                'cardinality' => 'one',
            ),
        ),
    );

}
