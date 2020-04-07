<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modAccessMenu extends \MODX\Revolution\modAccessMenu
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'access_menus',
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
                'class' => 'MODX\\Revolution\\modMenu',
                'local' => 'target',
                'foreign' => 'text',
                'owner' => 'foreign',
                'cardinality' => 'one',
            ),
        ),
    );

}
