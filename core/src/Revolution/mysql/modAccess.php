<?php
namespace MODX\Revolution\mysql;

use xPDO\xPDO;

class modAccess extends \MODX\Revolution\modAccess
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
            'target' => '',
            'principal_class' => 'MODX\\Revolution\\modPrincipal',
            'principal' => 0,
            'authority' => 9999,
            'policy' => 0,
        ),
        'fieldMeta' => 
        array (
            'target' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '100',
                'phptype' => 'string',
                'null' => false,
                'default' => '',
                'index' => 'fk',
            ),
            'principal_class' => 
            array (
                'dbtype' => 'varchar',
                'precision' => '100',
                'phptype' => 'string',
                'null' => false,
                'default' => 'MODX\\Revolution\\modPrincipal',
                'index' => 'index',
            ),
            'principal' => 
            array (
                'dbtype' => 'int',
                'precision' => '10',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'fk',
            ),
            'authority' => 
            array (
                'dbtype' => 'int',
                'precision' => '10',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'null' => false,
                'default' => 9999,
                'index' => 'index',
            ),
            'policy' => 
            array (
                'dbtype' => 'int',
                'precision' => '10',
                'attributes' => 'unsigned',
                'phptype' => 'integer',
                'null' => false,
                'default' => 0,
                'index' => 'fk',
            ),
        ),
        'indexes' => 
        array (
            'target' => 
            array (
                'alias' => 'target',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'target' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'principal_class' => 
            array (
                'alias' => 'principal_class',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'principal_class' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'principal' => 
            array (
                'alias' => 'principal',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'principal' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'authority' => 
            array (
                'alias' => 'authority',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'authority' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
            'policy' => 
            array (
                'alias' => 'policy',
                'primary' => false,
                'unique' => false,
                'type' => 'BTREE',
                'columns' => 
                array (
                    'policy' => 
                    array (
                        'length' => '',
                        'collation' => 'A',
                        'null' => false,
                    ),
                ),
            ),
        ),
        'aggregates' => 
        array (
            'Policy' => 
            array (
                'class' => 'MODX\\Revolution\\modAccessPolicy',
                'local' => 'policy',
                'foreign' => 'id',
                'owner' => 'foreign',
                'cardinality' => 'one',
            ),
            'Principal' => 
            array (
                'class' => 'MODX\\Revolution\\modPrincipal',
                'local' => 'principal',
                'foreign' => 'id',
                'owner' => 'foreign',
                'cardinality' => 'one',
            ),
            'GroupPrincipal' => 
            array (
                'class' => 'MODX\\Revolution\\modUserGroup',
                'local' => 'principal',
                'foreign' => 'id',
                'owner' => 'foreign',
                'cardinality' => 'one',
                'criteria' => 
                array (
                    'local' => 
                    array (
                        'principal_class' => 'MODX\\Revolution\\modUserGroup',
                    ),
                ),
            ),
            'UserPrincipal' => 
            array (
                'class' => 'MODX\\Revolution\\modUserGroup',
                'local' => 'principal',
                'foreign' => 'id',
                'owner' => 'foreign',
                'cardinality' => 'one',
                'criteria' => 
                array (
                    'local' => 
                    array (
                        'principal_class' => 'MODX\\Revolution\\modUser',
                    ),
                ),
            ),
            'MinimumRole' => 
            array (
                'class' => 'MODX\\Revolution\\modUserGroupRole',
                'local' => 'authority',
                'foreign' => 'authority',
                'owner' => 'local',
                'cardinality' => 'one',
            ),
        ),
    );

}
