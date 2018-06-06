<?php
$xpdo_meta_map = [
    'version' => '3.0',
    'namespace' => 'MODX\\Sources',
    'namespacePrefix' => '',
    'class_map' =>
        [
            'MODX\\modAccess' =>
                [
                    0 => 'MODX\\Sources\\modAccessMediaSource',
                ],
            'MODX\\modAccessibleObject' =>
                [
                    0 => 'MODX\\Sources\\modMediaSource',
                ],
            'MODX\\modMediaSource' =>
                [
                    0 => 'MODX\\Sources\\modFileMediaSource',
                    1 => 'MODX\\Sources\\modS3MediaSource',
                    2 => 'MODX\\Sources\\modFTPMediaSource',
                ],
            'xPDO\\Om\\xPDOObject' =>
                [
                    0 => 'MODX\\Sources\\modMediaSourceContext',
                    1 => 'MODX\\Sources\\modMediaSourceElement',
                ],
        ],
];