<?php
namespace MODX\Revolution\sqlsrv;

use xPDO\xPDO;

class modPrincipal extends \MODX\Revolution\modPrincipal
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'extends' => 'xPDO\\Om\\xPDOSimpleObject',
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
                'class' => 'MODX\\Revolution\\modAccess',
                'local' => 'id',
                'foreign' => 'principal',
                'cardinality' => 'many',
                'owner' => 'local',
            ),
        ),
    );

}
