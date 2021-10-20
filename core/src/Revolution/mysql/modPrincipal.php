<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modPrincipal extends \MODX\Revolution\modPrincipal
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
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
        'composites' => 
        array (
            'Acls' => 
            array (
                'class' => 'modAccess',
                'local' => 'id',
                'foreign' => 'principal',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
        ),
    );

}
