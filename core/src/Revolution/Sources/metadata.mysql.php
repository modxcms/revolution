<?php
$xpdo_meta_map = array (
    'version' => '3.0',
    'namespace' => 'MODX\\Revolution\\Sources',
    'namespacePrefix' => 'MODX',
    'class_map' => 
    array (
        'MODX\\Revolution\\modAccess' => 
        array (
            0 => 'MODX\\Revolution\\Sources\\modAccessMediaSource',
        ),
        'MODX\\Revolution\\modAccessibleSimpleObject' => 
        array (
            0 => 'MODX\\Revolution\\Sources\\modMediaSource',
        ),
        'MODX\\Revolution\\Sources\\modMediaSource' => 
        array (
            0 => 'MODX\\Revolution\\Sources\\modFileMediaSource',
            1 => 'MODX\\Revolution\\Sources\\modS3MediaSource',
            2 => 'MODX\\Revolution\\Sources\\modFTPMediaSource',
        ),
        'xPDO\\Om\\xPDOObject' => 
        array (
            0 => 'MODX\\Revolution\\Sources\\modMediaSourceContext',
            1 => 'MODX\\Revolution\\Sources\\modMediaSourceElement',
        ),
    ),
);