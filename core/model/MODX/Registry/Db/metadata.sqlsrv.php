<?php
$xpdo_meta_map = [
    'version' => '3.0',
    'namespace' => 'MODX\\Registry\\Db',
    'namespacePrefix' => '',
    'class_map' =>
        [
            'xPDO\\Om\\xPDOSimpleObject' =>
                [
                    0 => 'MODX\\Registry\\Db\\modDbRegisterQueue',
                    1 => 'MODX\\Registry\\Db\\modDbRegisterTopic',
                ],
            'xPDO\\Om\\xPDOObject' =>
                [
                    0 => 'MODX\\Registry\\Db\\modDbRegisterMessage',
                ],
        ],
];