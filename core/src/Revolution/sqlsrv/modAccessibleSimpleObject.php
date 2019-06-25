<?php
namespace MODX\Revolution\sqlsrv;

use xPDO\xPDO;

class modAccessibleSimpleObject extends \MODX\Revolution\modAccessibleSimpleObject
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'extends' => 'MODX\\Revolution\\modAccessibleObject',
        'fields' => 
        array (
            'id' => NULL,
        ),
        'fieldMeta' => 
        array (
            'id' => 
            array (
                'dbtype' => 'int',
                'phptype' => 'integer',
                'null' => false,
                'index' => 'pk',
                'generated' => 'native',
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
                    'id' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
        ),
    );

}
