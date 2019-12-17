<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modElement extends \MODX\Revolution\modElement
{

    public static $metaMap = array (
        'package' => 'MODX\\Revolution\\',
        'version' => '3.0',
        'table' => 'site_element',
        'extends' => 'MODX\\Revolution\\modAccessibleSimpleObject',
        'tableMeta' => 
        array (
            'engine' => 'InnoDB',
        ),
        'fields' => 
        array (
            'source' => 0,
            'property_preprocess' => 0,
        ),
        'fieldMeta' => 
        array (
            'source' => 
            array (
                'dbtype' => 'int',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'fk',
            ),
            'property_preprocess' => 
            array (
                'dbtype' => 'tinyint',
                'precision' => '1',
                'attributes' => 'unsigned',
                'phptype' => 'boolean',
                'null' => false,
                'default' => 0,
            ),
        ),
        'composites' => 
        array (
            'Acls' => 
            array (
                'class' => 'MODX\\Revolution\\modAccessElement',
                'local' => 'id',
                'foreign' => 'target',
                'owner' => 'local',
                'cardinality' => 'many',
            ),
        ),
        'aggregates' => 
        array (
            'CategoryAcls' => 
            array (
                'class' => 'MODX\\Revolution\\modAccessCategory',
                'local' => 'category',
                'foreign' => 'target',
                'owner' => 'local',
                'cardinality' => 'many',
            ),
            'Source' => 
            array (
                'class' => 'MODX\\Revolution\\Sources\\modMediaSource',
                'local' => 'source',
                'foreign' => 'id',
                'owner' => 'foreign',
                'cardinality' => 'one',
            ),
        ),
    );

}
